<?php

namespace App\Controller;

use App\Services\FileHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
    public function home(Request $request): Response
    {
        $loc = $this->locale($request);
        $fh = new FileHandler();
        $content = $fh->getFileContent('templates/'.$loc.'/adminhome.html');
        return $this->render('admin/boothome.html.twig', [
            "locale" =>  $this->localeSwitcher->getLocale(),
            "page" => $content
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/switch/{locale}', name: 'bootadmin.switch')]
    public function switch(Request $request, string $locale): Response
    {
        $request->getSession()->set('bootadmin.lang', $locale);
        $this->localeSwitcher->setLocale($locale);
        $fh = new FileHandler();
        $content = $fh->getFileContent('templates/'.$locale.'/adminhome.html');
        return $this->render('admin/boothome.html.twig', [
            "locale" =>  $this->localeSwitcher->getLocale(),
            "page" => $content
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/metals', name: 'bootadmin.metals')]
    public function AdminMetals(Request $request): Response
    {
        $loc = $this->locale($request);
        return $this->render('admin/metals.html.twig', [
            "locale" =>  $loc,
            "new" =>  false,
            ]
        );               
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
