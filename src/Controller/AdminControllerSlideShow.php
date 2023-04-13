<?php

namespace App\Controller;

use Exception;
use App\Entity\SlideShow;
use App\Services\DBlogger;
use App\Services\Uploader;

use App\Entity\SlideImages;
use App\Form\SlideShowType;
use App\Repository\SlideShowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\SlideShow as EntitySlideShow;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
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
    //      S L I D E  S H O W   S E R V I C E S 
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
        $allslides = $repo->findBy([], [ 'datein' => 'ASC']);
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
                    $this->addFlash('error', $translator->trans('images.errorupload'));
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
                    $this->addFlash('error', $translator->trans('images.errorupload'));
                }
            }
        }
        $allshow = $entityManager->getRepository(SlideShow::class)->findBy([], [ 'datein' => 'ASC']);
        return $this->render('admin/slide.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'locale' => $loc,
            'slide' => $slideshow,
            'allslides' => $allshow
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
                'message' => 'bootadmin.knives.photoswap OK',
                'trace' => $trace,
                'imageslist' => $imageslist
            ], 200);    
        }
        catch(Exception $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'data' => $data, 
                'payload' => $payload,
                'imageslist' => $imageslist
            ], 400);
        }
    }
    // --------------------------------------------------------------------------
    // P R I V A T E     S E R V I C E S 
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
