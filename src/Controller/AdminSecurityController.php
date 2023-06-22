<?php

namespace App\Controller;

use Exception;

use App\Entity\Handle;
use App\Entity\Metals;
use App\Entity\Category;
use App\Entity\Mechanism;
use App\Entity\Accessories;

use App\Form\MechanismType;
use App\Form\HandleType;
use App\Form\CategoryType;
use App\Form\MetalsType;
use App\Form\AccessoriesType;

use App\Services\FileHandler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/securitybootadmin')]
class AdminSecurityController extends AbstractController
{
    private LocaleSwitcher $localeSwitcher;
    // --------------------------------------------------------------------------
    public function __construct(LocaleSwitcher $localeSwitcher)
    {
        $this->localeSwitcher = $localeSwitcher;
    }
    // --------------------------------------------------------------------------
    #[Route('/login', name: 'bootadmin.login')]
    public function login(Request $request): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        if ($this->getUser()) {
            return $this->redirectToRoute('bootadmin.home');
        }
        return $this->render('admin/login.html.twig', [
            "locale" =>  $this->localeSwitcher->getLocale(),
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route(path: '/logout', name: 'bootadmin.logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    // --------------------------------------------------------------------------
    #[Route('/switch/{locale}/{origin}', name: 'bootadmin.switch')]
    public function switch(Request $request, string $locale, string $origin): Response
    {
        $request->getSession()->set('bootadmin.lang', $locale);
        $this->localeSwitcher->setLocale($locale);
        $fh = new FileHandler();
        // origin is used to return to the public or administration page
        if($origin == 'public') {
            return $this->render('public.html.twig', [
                "locale" =>  $this->localeSwitcher->getLocale(),
                ]
            );               
        }
        else {
            $content = $fh->getFileContent('templates/'.$locale.'/adminhome.html');
            return $this->render('admin/boothome.html.twig', [
                "locale" =>  $this->localeSwitcher->getLocale(),
                "page" => $content
                ]
            );               
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
