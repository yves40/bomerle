<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\Newsletter;
use App\Form\NewsletterType;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use SebastianBergmann\Environment\Console;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/public')]
class SiteController extends AbstractController
{
    private LocaleSwitcher $localeSwitcher;

    public function __construct(LocaleSwitcher $localeSwitcher) {
        $this->localeSwitcher = $localeSwitcher;
    }
    // ------------------------------------------------------------------------
    // Very old home page version...
    // ------------------------------------------------------------------------
    #[Route('/home', name: 'home')]
    public function home(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $newsletter = new Newsletter();
        $events = $entityManager->getRepository(Events::class)->listEvents();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($newsletter);
            $entityManager->flush();
        }
        return $this->render('home.html.twig', [
            'formnewsletter' => $form->createView(),
            "events" => $events
            ]);
    }
    // ------------------------------------------------------------------------
    // Public page lang switch handler
    // ------------------------------------------------------------------------
    #[Route('/switchlang', name: 'switchlang')]
    public function switchlang(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $current = $request->getSession()->get('bootadmin.lang');
        switch($current) {
            case 'en':
                $request->getSession()->set('bootadmin.lang', 'fr');
                $this->localeSwitcher->setLocale('fr');
                break;
            case 'fr':
                $request->getSession()->set('bootadmin.lang', 'en');
                $this->localeSwitcher->setLocale('en');
                break;
        }
        return $this->render('main.html.twig', [ ]);
    }

}
