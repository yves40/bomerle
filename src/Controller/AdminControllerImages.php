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
        dump($sdirlist);
        return $this->render('admin/images.html.twig', [
            'knifeloc' => $knifeimagesloc,
            'slideloc' => $slideshowimagesloc,
            'klist' => $kdirlist,
            'slist' => $sdirlist,
            'locale' => $loc
        ]);
    }
    // --------------------------------------------------------------------------
    private function checkUsage($imagelist, $repo) {
        
        foreach($imagelist as $key => $img) {
            $usedimages = $repo->findImage($img['name']);   // Check image is used by a knife or slideshow
            // dump($usedimages);
            if(empty($usedimages)) {
                $imagelist[$key]['used'] = false;
                $imagelist[$key]['id'] = 0;
            }
            else {
                $imagelist[$key]['used'] = true;
                $imagelist[$key]['id'] = $usedimages[0]->getId();
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
