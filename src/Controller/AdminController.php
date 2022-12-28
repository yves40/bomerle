<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/bootadmin')]
class AdminController extends AbstractController
{
    #[Route('/home', name: 'bootadmin.home')]
    public function home(): Response
    {
        return $this->render('admin/boothome.html.twig');               
    }
}