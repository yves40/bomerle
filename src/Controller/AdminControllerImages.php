<?php

namespace App\Controller;

use App\Services\FileHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[Route('/bootadmin')]
class AdminControllerImages extends AbstractController
{
    #[Route('/images/list', name: 'bootadmin.images.list')]
    public function index(ParameterBagInterface $params): Response
    {
        $knifeimagesloc = $params->get('knifeimages_directory');
        $slideshowimagesloc = $params->get('slideshowimages_directory');
        $fh = new FileHandler();
        $kdirlist = $fh->getFilesList($knifeimagesloc);
        $sdirlist = $fh->getFilesList($slideshowimagesloc);
        return $this->render('admin_controller_images/index.html.twig', [
            'controller_name' => 'AdminControllerImages',
            'knifeloc' => $knifeimagesloc,
            'slideloc' => $slideshowimagesloc,
            'klist' => $kdirlist,
            'slist' => $sdirlist
        ]);
    }
}
