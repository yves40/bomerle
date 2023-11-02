<?php

namespace App\Controller;

use App\Entity\SlideShow;
use App\Form\SlideShowType;
use App\Services\DBlogger;
use App\Services\FileHandler;

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
    public function index(Request $request, ParameterBagInterface $params): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        $knifeimagesloc = $params->get('knifeimages_directory');
        $slideshowimagesloc = $params->get('slideshowimages_directory');
        $fh = new FileHandler();
        $kdirlist = $fh->getFilesList($knifeimagesloc, FileHandler::EXCLUDE_DIR);
        $sdirlist = $fh->getFilesList($slideshowimagesloc, FileHandler::EXCLUDE_DIR);
        return $this->render('admin/images.html.twig', [
            'knifeloc' => $knifeimagesloc,
            'slideloc' => $slideshowimagesloc,
            'klist' => $kdirlist,
            'slist' => $sdirlist,
            'locale' => $loc
        ]);
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
