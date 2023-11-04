<?php

namespace App\Controller;

use App\Entity\Images;
use App\Services\DBlogger;
use App\Entity\SlideImages;

use App\Services\FileHandler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[Route('/adminimages')]
class AdminControllerImages extends AbstractController
{
    private LocaleSwitcher $localeSwitcher;
    private DBLogger $dblog;
    const MODULE = 'AdminControllerImages';

    public function __construct(LocaleSwitcher $localeSwitcher, DBlogger $dblog)
    {
        $this->localeSwitcher = $localeSwitcher;
        $this->dblog = $dblog;
    }
    // --------------------------------------------------------------------------
    #[Route('/protected/list', name: 'bootadmin.images.list')]
    public function index(Request $request,
                                EntityManagerInterface $entityManager,
                                ParameterBagInterface $params): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        $knifeimagesloc = $params->get('knifeimages_directory');
        $slideshowimagesloc = $params->get('slideshowimages_directory');
        $fh = new FileHandler();
        // get all physical files in directories
        $kdirlist = $fh->getFilesList($knifeimagesloc, FileHandler::EXCLUDE_DIR);
        $sdirlist = $fh->getFilesList($slideshowimagesloc, FileHandler::EXCLUDE_DIR);
        $kdirlist = $this->checkUsage($kdirlist, $entityManager->getRepository(Images::class));   // Are these images really used by knives or slideshow ? 
        $sdirlist = $this->checkUsage($sdirlist, $entityManager->getRepository(SlideImages::class));
        return $this->render('admin/images.html.twig', [
            'knifeloc' => $knifeimagesloc,
            'slideloc' => $slideshowimagesloc,
            'klist' => $kdirlist,
            'slist' => $sdirlist,
            'locale' => $loc
        ]);
    }
    // --------------------------------------------------------------------------
    #[Route('/protected/convert/{id?0}/{imagefor}', name: 'bootadmin.images.convert')]
    public function convertImage(Request $request,
                                int $id,
                                string $imagefor,    // Image for a knife or slide ?
                                EntityManagerInterface $entityManager,
                                ParameterBagInterface $params): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        $knifeimagesloc = $params->get('knifeimages_directory');
        $slideshowimagesloc = $params->get('slideshowimages_directory');
        $fh = new FileHandler();
        /**
         * @var Images $image
         */
        switch($imagefor) {
            case 'knife':
                $repo = $entityManager->getRepository(Images::class);
                $image = $repo->find($id);
                $convertedname = $fh->convertToWebp($knifeimagesloc, $image->getFilename());
                if($convertedname != null) {
                    $image->setFilename($convertedname);
                    $entityManager->persist($image);
                    $entityManager->flush();
                }
                break;
            case 'slide':
                $repo = $entityManager->getRepository(SlideImages::class);
                $image = $repo->find($id);
                $convertedname = $fh->convertToWebp($slideshowimagesloc, $image->getFilename());
                if($convertedname != null) {
                    $image->setFilename($convertedname);
                    $entityManager->persist($image);
                    $entityManager->flush();
                }
                break;
        }
        return $this->redirectToRoute('bootadmin.images.list');
    }
    // --------------------------------------------------------------------------
    private function checkUsage($imagelist, $repo) {
        foreach($imagelist as $key => $img) {
            $usedimages = $repo->findImage($img['name']);   // Check image is used by a knife or slideshow
            if(empty($usedimages)) {
                $imagelist[$key]['used'] = false;
                $imagelist[$key]['id'] = 0;
                $imagelist[$key]['relatedobject'] = 'None';
            }
            else {
                $imagelist[$key]['used'] = true;
                $imagelist[$key]['id'] = $usedimages[0]->getId();
                $imagelist[$key]['class'] = 'none';
                if(get_class($repo) == 'App\Repository\ImagesRepository'){
                    $imagelist[$key]['relatedobject'] = $usedimages[0]->getKnifes()->getName();
                }
                else {
                    $imagelist[$key]['relatedobject'] = $usedimages[0]->getSlideshow()->getName();
                }
            }
        }
        return $imagelist;
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
