<?php

namespace App\Controller;

use DateTime;
use Exception;

use App\Entity\SlideShow;
use App\Services\DBlogger;
use App\Services\Uploader;
use App\Entity\SlideImages;
use App\Form\SlideShowType;
use App\Repository\SlideShowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/slides')]
class AdminControllerSlideShow extends AbstractController
{

    private LocaleSwitcher $localeSwitcher;
    private DBLogger $dblog;
    const rotationstep = 90;
    const MODULE = 'AdminControllerSlideShow';

    // --------------------------------------------------------------------------
    public function __construct(LocaleSwitcher $localeSwitcher, DBlogger $dblog)
    {
        $this->localeSwitcher = $localeSwitcher;
        $this->dblog = $dblog;
    }
    // --------------------------------------------------------------------------
    //      S L I D E S H O W   S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/protected/list', name: 'bootadmin.slideshow.list')]
    public function list(Request $request,
                    EntityManagerInterface $entityManager,
                    TranslatorInterface $translator
                ): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        /** 
         * @var SlideShowRepository $repo 
         * 
        */
        $repo = $entityManager->getRepository(SlideShow::class);
        $allslides = $repo->findBy([], [ 'datein' => 'DESC']);
        $slide = new SlideShow();
        $form = $this->createForm(SlideShowType::class, $slide);
        return $this->render('admin/slides.html.twig', [
            'form' => $form->createView(),
            'allslides' => $allslides,
            'locale' => $loc
        ]);
    }
    // --------------------------------------------------------------------------
    #[Route('/protected/edit/{id?0}', name: 'bootadmin.slide.edit')]
    public function edit(Request $request,
                    int $id, 
                    Uploader $uploader,
                    EntityManagerInterface $entityManager,
                    TranslatorInterface $translator
                ): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        $rank = 0;          // Used to order images in the knife photo catalog
        if($id === 0) { // Create ?
            $slideshow = new SlideShow();
            $form = $this->createForm(SlideShowType::class, $slideshow);
            $form->handleRequest($request);
            if($form->isSubmitted()) {
                if($form->isValid()) {
                    // Take care of uploaded images
                    $uploadedFiles = $form->get('slides')->getData();
                    $physicalPath = $this->getParameter('slideshowimages_directory');
                    for($i=0; $i < count($uploadedFiles); $i++){
                        $image = new SlideImages();
                        $newFileName = $uploader->uploadFile($uploadedFiles[$i], $physicalPath);
                        $image->setFilename($newFileName);
                        $image->setRank(++$rank);
                        $slideshow->addSlide($image);
                    }    
                    // Manage other properties
                    $slideshow->setName($slideshow->getName());
                    $entityManager->persist($slideshow);
                    $entityManager->flush();
                    $this->addFlash('success', $slideshow->getName().': '.$translator->trans('admin.manageslides.created'));
                    $this->dblog->info($slideshow->getName().' created',
                        'SlideShow CREATION',
                        self::MODULE,
                        $request->getSession()->get('email')
                    );
                    $slideshow = new SlideShow();
                }
                else {
                    $errors = $form->getErrors(true, false);
                    foreach ($errors as $fielderrors) {
                        foreach($fielderrors as $error) {
                            $this->addFlash('error', $error->getMessage());
                        }
                    }                    
                }
            }
        }
        else{   // Read / Update
            $slideshow = $entityManager->getRepository(SlideShow::class)->findOneBy([ 'id' => $id]);
            $slideimages = $entityManager->getRepository(SlideImages::class);
            $rank = $slideimages->getMaxRank($slideshow);
            $form = $this->createForm(SlideShowType::class, $slideshow);
            $form->handleRequest($request);
            if($form->isSubmitted()) {
                if($form->isValid()) {
                    // Take care of uploaded images
                    $uploadedFiles = $form->get('slides')->getData();
                    $physicalPath = $this->getParameter('slideshowimages_directory');
                    for($i=0; $i < count($uploadedFiles); $i++){
                        $image = new SlideImages();
                        $newFileName = $uploader->uploadFile($uploadedFiles[$i], $physicalPath);
                        $image->setFilename($newFileName);
                        $image->setRank(++$rank);
                        $slideshow->addSlide($image);
                    }    
                    // Manage other properties
                    $entityManager->persist($slideshow);
                    $entityManager->flush();
                    $this->addFlash('success', $slideshow->getName().': '.$translator->trans('admin.manageslides.updated'));
                    $this->dblog->info($slideshow->getName().' updated',
                        'SlideShow UPDATE',
                        self::MODULE,
                        $request->getSession()->get('email')
                    );
                }
                else {
                    $errors = $form->getErrors(true, false);
                    foreach ($errors as $fielderrors) {
                        foreach($fielderrors as $error) {
                            $this->addFlash('error', $error->getMessage());
                        }
                    }                    
                }
            }
        }
        $allshow = $entityManager->getRepository(SlideShow::class)->findBy([], [ 'name' => 'ASC', 'datein' => 'DESC']);
        return $this->render('admin/slide.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'locale' => $loc,
            'slide' => $slideshow,
            'allslides' => $allshow,
        ]);
    }
    // --------------------------------------------------------------------------
    #[Route('/protected/delete/{id}', name: 'bootadmin.slide.delete')]
    public function delete(Request $request,
                    int $id, 
                    Uploader $uploader,
                    EntityManagerInterface $entityManager,
                    TranslatorInterface $translator
                ): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        //
        $repo = $entityManager->getRepository(SlideShow::class);
        $slideshow = $repo->findOnebY(['id' => $id ]);
        // Cleanup the uploaded files from the file system
        $images = $slideshow->getSlides();
        $physicalPath = $this->getParameter('slideshowimages_directory');
        foreach($images as $img) {
            $uploader->deleteFile($physicalPath.'/'.$img->getFilename());
        };
        // Cleanup the DB
        $entityManager->remove($slideshow);
        $entityManager->flush();
        $this->addFlash('success', $slideshow->getName().': '.$translator->trans('admin.manageslides.deleted'));
        $this->dblog->info($slideshow->getName().' deleted',
            'SlideShow DELETION',
            self::MODULE,
            $request->getSession()->get('email')
        );
        //  Back to the slideshow list
        $slideshow = new SlideShow();
        $allshow = $repo->findBy([], [ 'datein' => 'DESC']);
        $form = $this->createForm(SlideShowType::class, $slideshow);
        return $this->render('admin/slides.html.twig', [
            'form' => $form->createView(),
            'allslides' => $allshow,
            'locale' => $loc
        ]);
    }
    // --------------------------------------------------------------------------
    // J S O N    S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/protected/removephoto', name: 'bootadmin.slides.removephoto')]
    public function removePhoto(Request $request,
        Uploader $uploader,
        EntityManagerInterface $emgr)
    {
        $data = file_get_contents("php://input");
        $payload = json_decode($data, true);
        $showid = $payload['showid'];
        $imageid = $payload['imageid'];

        $loc = $this->locale($request);
        $imagepath = $this->getParameter('slideshowimages_directory');  // Defined in services.yaml

        try {
            $slideshow = $emgr->getRepository(SlideShow::class)->findOneBy([ 'id' => $showid]);
            $image = $emgr->getRepository(SlideImages::class)->findOneBy([ 'id' => $imageid]);
            if(empty($image)) {
                return $this->json([
                    'message' => "Image with ID :" . $imageid . "non trouvée !!" 
                ], 400);
            }
            else {
                $emgr->getRepository(SlideImages::class)->remove($image);
                $uploader->deleteFile($imagepath . '/'. $image->getFilename());
            }
            $slideshow->removeSlide($image);    // Shoot image from show object
            $emgr->persist($slideshow);
            $emgr->flush();
            // Reset the rank column after image deletion
            $imglist = $emgr->getRepository(SlideImages::class)->findSlideshowImagesByRank($slideshow);
            $rank = 0;
            foreach($imglist as $key => $oneimage) {
                $oneimage->setRank(++$rank);
                $emgr->persist($oneimage);
            }
            $emgr->flush();
            return $this->json([
                'message' => 'bootadmin.slides.photodelete OK',
                'showid' => $showid,
                'imageid' => $imageid,
                'reordered' => $rank
            ], 200);
        }
        catch(Exception $e) {
            return $this->json([
                'message' => 'bootadmin.slides.photodelete KO',
                'showid' => $showid,
                'imageid' => $imageid
            ], 500);
        }
}
    // --------------------------------------------------------------------------
    #[Route('/protected/swapphotos', name: 'bootadmin.slides.swapphotos')]
    public function swapPhotos(Request $request,
        EntityManagerInterface $emgr)
    {
        $trace = [];
        try {
            $data = file_get_contents("php://input");
            $payload = json_decode($data, true);
            $imageslist = $payload['imagedata'];

            $loc = $this->locale($request);
            $repo = $emgr->getRepository(SlideImages::class);
            $rankindex = 0;
            foreach($imageslist as $key => $value) {
                array_push($trace, $value['imageid']);
                $img = $repo->findOneBy([ 'id' => $value['imageid']]);
                if($img === null) {
                    throw new Exception('y a une erreur');
                }
                $img->setRank(++$rankindex);
                $emgr->persist($img);
            }
            $emgr->flush();
            return $this->json([
                'message' => 'bootadmin.slides.photoswap OK',
                'trace' => $trace,
                'imageslist' => $imageslist
            ], 200);    
        }
        catch(Exception $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'data' => $data, 
                'payload' => $payload,
                // 'imageslist' => $imageslist
            ], 400);
        }
    }
    // --------------------------------------------------------------------------
    #[Route('/protected/rotatephoto', name: 'bootadmin.slides.rotatephoto')]
    public function rotatePhoto(Request $request,
        EntityManagerInterface $emgr)
    {
        try {
            $data = file_get_contents("php://input");
            $payload = json_decode($data, true);
            $slideid = $payload['slideid'];
            $imageid = $payload['imageid'];

            $repo = $emgr->getRepository(SlideImages::class);
            $rotated = $repo->findOneBy([ 'id' => $imageid ]);
            $currentrotation = $rotated->getRotation();
            $currentrotation += AdminControllerSlideShow::rotationstep;
            if($currentrotation === 360) {
                $currentrotation = 0;
            }
            $rotated->setRotation($currentrotation);
            $emgr->persist($rotated);
            $emgr->flush();
            return $this->json([
                'message' => 'bootadmin.slideshow.rotatephoto OK',
                'slideid' => $slideid,
                'imageid' => $imageid,
                'rotation' => $rotated->getRotation()
            ], 200);    
        }
        catch(Exception $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    // --------------------------------------------------------------------------
    #[Route('/public/getnews', name: 'bootadmin.slides.getnews')]
    public function getNews(Request $request, EntityManagerInterface $em) {
        try {
            $data = file_get_contents("php://input");
            $payload = json_decode($data, true);
            $newsname = $payload['newsname'];
            $slides = $em->getRepository(SlideShow::class)->findBy([ 'name' => $newsname, 
                                                                    'active' => true]);
            $candidatescount = count($slides) ;
            /**  @var SlideImages $img */
            /**  @var SlideShow $selectedslide */
            // Evaluate slide show validity
            $selectedslide = $this->selectSlideshow($slides);
            $images = [];
            $imagesrotation = [];
            if($selectedslide->getId()) {    // One valid slide show selected ?
                $slidermode = $selectedslide->isSlider();
                $timing = $selectedslide->getTiming();
                $daterange = $selectedslide->isDaterange();
                $datein = $selectedslide->getDatein();
                $dateout = $selectedslide->getDateout();
                $monday = $selectedslide->isMonday();
                $tuesday = $selectedslide->isTuesday();
                $wednesday = $selectedslide->isWednesday();
                $thursday = $selectedslide->isThursday();
                $friday = $selectedslide->isFriday();
                $saturday = $selectedslide->isSaturday();
                $sunday = $selectedslide->isSunday();
                $text = $selectedslide->getDescription();
                // Slide show selected, get images
                $imglist = $em->getRepository(SlideImages::class)->findSlideshowImagesByRank($selectedslide);
                foreach($imglist as $img){
                    array_push($images, $img->getFilename());
                    array_push($imagesrotation, $img->getRotation());
                }
            }
            else {
                date_default_timezone_set('Europe/Paris');
                // Get current date
                $currentDate = date('Y-m-d');        
                $slidermode = false;
                $daterange = false;
                $datein = $dateout = $currentDate;
                $monday = $tuesday = $wednesday = $thursday = $friday = $saturday = $sunday = false;
                $text = '';
            }
            return $this->json([
                'message' => 'bootadmin.slides.getnews OK',
                'newsname' => $newsname,
                'newsdescription' => $text,
                'candidatescount' => $candidatescount,
                'slidermode' => $slidermode,
                'timing' => $timing,
                'daterange' => $daterange,
                'datein' => $datein,
                'dateout' => $dateout,
                'Monday' => $monday,
                'Tuesday' => $tuesday,
                'Wednesday' => $wednesday,
                'Thursday' => $thursday,
                'Friday' => $friday,
                'Saturday' => $saturday,
                'Sunday' => $sunday,
                'newsimages' => $images,
                'imagesrotation' => $imagesrotation
            ], 200);    
        }
        catch(Exception $e) {
            return $this->json([
                'message' => 'bootadmin.slides.getdiapo KO',
                'newsname' => $newsname,
                'error' => $e
            ], 400);        
        }
    }
    // --------------------------------------------------------------------------
    #[Route('/public/getactivenews', name: 'slides.public.getactivenews')]
    public function getActiveNews(Request $request, EntityManagerInterface $em) {
        try {
                $active = [];
                // $slides = $em->getRepository(SlideShow::class)->findBy(['active' => true ],
                //                                                 array('name' => 'ASC'));
                $now = new DateTime();
                $slides = $em->getRepository(SlideShow::class)->findDistinctActiveNews($now);
                return $this->json([
                    'message' => 'bootadmin.slides.getactivenews OK',
                    'activecount' => count($slides),
                    'activenews' => $slides
                ], 200);        
        }
        catch(Exception $e) {
            return $this->json([
                'message' => 'bootadmin.slides.getactivenews KO',
                'error' => $e
            ], 400);        
        }
    }
    // --------------------------------------------------------------------------
    // P R I V A T E     S E R V I C E S 
    // --------------------------------------------------------------------------
    private function selectSlideshow($slides) : SlideShow {
        date_default_timezone_set('Europe/Paris');
        // Get current date
        $currentDate = date_timestamp_get(date_create());
        $dayofweek = date('w'); // 0 is sunday
        /**  @var SlideShow $slide */
        $selectedslide = new SlideShow();
        if(!empty($slides)) {
            $diffdays= 0;
            foreach($slides as $slide) {
                if($slide->isDaterange()){   // Have to check date ? 
                    $earlier = $slide->getDatein();
                    $later = $slide->getDateout();
                    if( (date_timestamp_get($earlier)  <= $currentDate) && // Current day within range ?
                        (date_timestamp_get($later) >= $currentDate) ) {
                        $diff = $later->diff($earlier)->format("%a");
                        if($diffdays == 0 || $diffdays > $diff) {           // If multi periods, take the shortest matching
                            if($this->specificDay($slide)) {    // Any day check ?
                                if($this->goodDay($slide, $dayofweek)) {
                                    $diffdays = $diff;
                                    $selectedslide = $slide;
                                }
                            }
                            else {
                                $diffdays = $diff;
                                $selectedslide = $slide;
                            }
                        }
                    }
                }
                else {  // No date range
                    if($this->specificDay($slide)) {    // Any day check ?
                        if($this->goodDay($slide, $dayofweek)) {
                            $selectedslide = $slide;
                        }
                    }
                    else {
                        $selectedslide = $slide;
                    }
                }
            }       
        }
        return $selectedslide;
    }
    // --------------------------------------------------------------------------
    /**  @var SlideShow $slide */
    private function specificDay($slide) {
        return $slide->isMonday() || $slide->isTuesday() 
                    || $slide->isWednesday() || $slide->isThursday() 
                    || $slide->isFriday() || $slide->isSaturday() 
                    || $slide-> isSunday();
    }
    // --------------------------------------------------------------------------
    /**  @var SlideShow $slide */
    private function goodDay($slide, $dayofweek) {
        switch($dayofweek) {
            case 0: return $slide->isSunday();
            case 1: return $slide->isMonday();
            case 2: return $slide->isTuesday();
            case 3: return $slide->isWednesday();
            case 4: return $slide->isThursday();
            case 5: return $slide->isFriday();
            case 6: return $slide->isSaturday();
        }
    }
    // --------------------------------------------------------------------------
    private function locale(Request $request) {
        $session = $request->getSession();
        if($session->has('bootadmin.lang')) {
            $loc = $session->get('bootadmin.lang');
        }
        else {
            $loc = $this->localeSwitcher->getLocale();
            $session->set('bootadmin.lang', $loc);
        }
        $this->localeSwitcher->setLocale($loc);
        return $loc;
    }
}
