<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;

#[Route('/bootadmin')]
class AdminController extends AbstractController
{
    private LocaleSwitcher $localeSwitcher;
    // --------------------------------------------------------------------------
    public function __construct(LocaleSwitcher $localeSwitcher)
    {
        $this->localeSwitcher = $localeSwitcher;
    }
    // --------------------------------------------------------------------------
    #[Route('/home', name: 'bootadmin.home')]
    public function home(): Response
    {
        return $this->render('admin/boothome.html.twig', [
            "locale" =>  $this->localeSwitcher->getLocale()
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/english', name: 'bootadmin.en')]
    public function english(): Response
    {
        $currentLocale = $this->localeSwitcher->getLocale();
        $this->localeSwitcher->setLocale('en');
        return $this->render('admin/boothome.html.twig', [
            "locale" =>  $this->localeSwitcher->getLocale()
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/french', name: 'bootadmin.fr')]
    public function french(): Response
    {
        $this->localeSwitcher->setLocale('fr');
        return $this->render('admin/boothome.html.twig', [
            "locale" =>  $this->localeSwitcher->getLocale()
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/locale', name: 'bootadmin.locale')]
    public function locale(): Response
    {
        return $this->render('admin/boothome.html.twig', [
            "locale" =>  $this->localeSwitcher->getLocale()
            ]
        );               
    }
}
