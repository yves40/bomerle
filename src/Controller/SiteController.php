<?php

namespace App\Controller;

use App\Entity\Events;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SiteController extends AbstractController
{
    public function __construct(private LocaleSwitcher $localeSwitcher) { }


    #[Route('/', name: 'home')]
    public function home(
        EntityManagerInterface $entityManager
    ): Response
    {
        $events = $entityManager->getRepository(Events::class)->listEvents();
        // $this->localeSwitcher->setlocale('en');
        return $this->render('home.html.twig', [
            "events" => $events
        ]);
    }

}
