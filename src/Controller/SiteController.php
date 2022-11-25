<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\Events;
use App\Form\NewsletterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;

class SiteController extends AbstractController
{
    public function __construct(private LocaleSwitcher $localeSwitcher) { }


    #[Route('/', name: 'home')]
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

    #[Route('/unsubscribenewsletter/{id}', name: 'home.unsubscribenewsletter')]
    public function unsubscribeNewsletter(): Response
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        return $this->render('home.html.twig', [
            'formnewsletter' => $form->createView()
        ]);
    }
}
