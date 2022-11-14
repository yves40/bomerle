<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SiteController extends AbstractController
{
    public function __construct(private LocaleSwitcher $localeSwitcher) { }


    #[Route('/', name: 'home')]
    public function home(): Response
    {
        // $this->localeSwitcher->setlocale('en');
        return $this->render('home.html.twig', []);
    }

}
