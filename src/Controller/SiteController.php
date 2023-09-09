<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\Newsletter;
use App\Entity\Contact;
use App\Form\NewsletterType;
use App\Form\ContactType;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/public')]
class SiteController extends AbstractController
{
    private LocaleSwitcher $localeSwitcher;

    public function __construct(LocaleSwitcher $localeSwitcher) {
        $this->localeSwitcher = $localeSwitcher;
    }
    // --------------------------------------------------------------------------
    #[Route('/main', name: 'public.main')]
    public function public(Request $request, 
                TranslatorInterface $translator): Response
    {
        $contact = new Contact();

        $formContact = $this->createForm(ContactType::class, $contact);
        $infogen = $translator->trans('reqtype-info');

        $formContact->handleRequest($request);
        $loc = $this->locale($request); // Set the proper language for translations
        return $this->render('main.html.twig', [
            "locale" =>  $this->localeSwitcher->getLocale(),
            'formcontact' => $formContact->createView(),
            ]
        );               
    }
    // ------------------------------------------------------------------------
    // Very old home page version...
    // ------------------------------------------------------------------------
    #[Route('/home', name: 'public.home')]
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
    #[Route('/switchlang/{locale}', name: 'public.switchlang')]
    public function switchlang(
        Request $request, string $locale
    ): Response
    {
        $request->getSession()->set('bootadmin.lang', $locale);
        $this->localeSwitcher->setLocale($locale);
        return $this->redirectToRoute('public.main');
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
