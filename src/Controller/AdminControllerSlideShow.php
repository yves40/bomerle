<?php

namespace App\Controller;

use stdClass;
use Exception;
use ArrayObject;
use App\Entity\SlideShow;

use App\Services\DBlogger;
use App\Services\Uploader;
use App\Entity\SlideImages;
use App\Form\SlideShowType;
use App\Services\FileHandler;
use App\Repository\SlideShowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
use Proxies\__CG__\App\Entity\SlideShow as EntitySlideShow;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/bootadmin')]
class AdminControllerSlideShow extends AbstractController
{

    private LocaleSwitcher $localeSwitcher;
    private DBLogger $dblog;
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
    #[Route('/slides/list', name: 'bootadmin.slideshow.list')]
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
        $allslides = $repo->findBy([], [ 'name' => 'ASC', 'datein' => 'ASC']);
        $slide = new SlideShow();
        $form = $this->createForm(SlideShowType::class, $slide);
        return $this->render('admin/slides.html.twig', [
            'form' => $form->createView(),
            'allslides' => $allslides,
            'locale' => $loc
        ]);
    }
    // --------------------------------------------------------------------------
    #[Route('/slides/edit/{id?0}', name: 'bootadmin.slide.edit')]
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
                    $lowername = strtolower($slideshow->getName());
                    $slideshow->setName($lowername);
                    $entityManager->persist($slideshow);
                    $entityManager->flush();
                    $this->addFlash('success', $lowername.': '.$translator->trans('admin.manageslides.created'));
                    $this->dblog->info($slideshow->getName().' created',
                        'SlideShow CREATION',
                        self::MODULE,
                        $request->getSession()->get('email')
                    );
                    $slideshow = new SlideShow();
                }
                else {
                    $errors = $form->getErrors(true, false);
                    dump($errors);
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
                    $lowername = strtolower($slideshow->getName());
                    $slideshow->setName($lowername);
                    $entityManager->persist($slideshow);
                    $entityManager->flush();
                    $this->addFlash('success', $lowername.': '.$translator->trans('admin.manageslides.updated'));
                    $this->dblog->info($slideshow->getName().' updated',
                        'SlideShow UPDATE',
                        self::MODULE,
                        $request->getSession()->get('email')
                    );
                }
                else {
                    $errors = $form->getErrors(true, false);
                    dump($errors);
                    foreach ($errors as $fielderrors) {
                        foreach($fielderrors as $error) {
                            $this->addFlash('error', $error->getMessage());
                        }
                    }                    
                }
            }
        }
        $allshow = $entityManager->getRepository(SlideShow::class)->findBy([], [ 'name' => 'ASC', 'datein' => 'ASC']);
        return $this->render('admin/slide.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'locale' => $loc,
            'slide' => $slideshow,
            'allslides' => $allshow,
        ]);
    }
    // --------------------------------------------------------------------------
    #[Route('/slides/delete/{id}', name: 'bootadmin.slide.delete')]
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
        //  Send back a clean page
        $slideshow = new SlideShow();
        $allshow = $entityManager->getRepository(SlideShow::class)->findBy([], [ 'datein' => 'ASC']);
        $form = $this->createForm(SlideShowType::class, $slideshow);
        return $this->render('admin/slide.html.twig', [
            'form' => $form->createView(),
            'id' => 0,
            'locale' => $loc,
            'slide' => $slideshow,
            'allslides' => $allshow
        ]);
    }
    // --------------------------------------------------------------------------
    // J S O N    S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/slides/removephoto', name: 'bootadmin.slides.removephoto')]
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
    #[Route('/slides/swapphotos', name: 'bootadmin.slides.swapphotos')]
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
    #[Route('/slides/getdiapos', name: 'bootadmin.slides.getdiapos')]
    public function getDiapos(Request $request, EntityManagerInterface $em) {
        try {
            $data = file_get_contents("php://input");
            $payload = json_decode($data, true);
            $diaponame = $payload['diaponame'];
            $slides = $em->getRepository(SlideShow::class)->findBy([ 'name' => $diaponame, 
                                                                    'active' => true]);
            $candidatescount = count($slides) ;
            /**  @var SlideImages $img */
            /**  @var SlideShow $selectedslide */
            // Evaluate slide show validity
            $selectedslide = $this->selectSlideshow($slides);
            $images = [];
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
                // Slide show selected, get images
                $imglist = $em->getRepository(SlideImages::class)->findSlideshowImagesByRank($selectedslide);
                foreach($imglist as $img){
                    array_push($images, $img->getFilename());
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
            }
            return $this->json([
                'message' => 'bootadmin.slides.getdiapos OK',
                'diaponame' => $diaponame,
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
                'images' => $images,
            ], 200);    
        }
        catch(Exception $e) {
            return $this->json([
                'message' => 'bootadmin.slides.getdiapo KO',
                'diaponame' => $diaponame,
                'error' => $e
            ], 400);        
        }
    }
    // --------------------------------------------------------------------------
    #[Route('/slides/slidertemplate', name: 'bootadmin.slides.slidertemplates')]
    public function getSliderTemplate(Request $request, EntityManagerInterface $em,
                                FileHandler $fh) {
        $htmlContent = $fh->getFileContent('templates/framework/Bslider.html');
        return $this->json([
            'template' => $htmlContent
        ]);
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
