<?php

namespace App\Controller;

use App\Entity\Events;
use App\Form\EventsType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
    #[Route('/events', name: 'app_events')]
    public function index(): Response
    {
        return $this->render('events/index.html.twig', [
            'controller_name' => 'EventsController',
        ]);
    }

    #[Route('addevent', name: 'event.add')]
    public function addEvent(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $new = true;
        $event = new Events();
        $eve = $entityManager->getRepository(Events::class);
        $events = $eve->listEvents();
        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($event);
            $entityManager->flush();
            $events = $eve->listEvents();
            $this->addFlash('success', "L'événement' ".$event->getName()." a été ajouté");
            $event = new Events();
            $form = $this->createForm(EventsType::class, $event);
            return $this->render('events/events.html.twig', [
                'formevent' => $form->createView(),
                'events' => $events,
                'new' => $new
            ]);
        }elseif($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('error', 'Un problème est survenu !');
            return $this->render('events/events.html.twig', [
                'formevent' => $form->createView(),
                'events' => $events,
                'new' => $new
            ]);
        }else{
            if($new){
                $eventTime = new DateTime('now', new \DateTimeZone('Europe/Paris'));
                $event->setDate($eventTime);
            }
            $form = $this->createForm(EventsType::class, $event);
            return $this->render('events/events.html.twig', [
                'formevent' => $form->createView(),
                'event' => $event,
                'events' => $events,
                'new' => $new
            ]);
        }
    }
    #[Route('deleteevent/{id}', name: 'event.delete')]
    public function deleteEvent(
        int $id,
        EntityManagerInterface $entityManager
    ): Response
    {
        $event = $entityManager->getRepository(Events::class)->find($id);
        $entityManager->getRepository(Events::class)->remove($event, true);
        $this->addFlash('success', "Le salon ".$event->getName()." a été supprimé");
        return $this->redirectToRoute('event.add');
    }
    #[Route('updateevent/{id}', name: 'event.update')]
    public function updateEvent(
        Events $event,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $new = false;
        $eve = $entityManager->getRepository(Events::class);
        $events = $eve->listEvents();
        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($event);
            $entityManager->flush();
            $events = $eve->listEvents();
            $this->addFlash('success', "Le salon ".$event->getName()." a été modifié");
            $event = new Events();
            $form = $this->createForm(EventsType::class, $event);
            return $this->redirectToRoute('event.add');
        }elseif($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('error', 'Un problème est survenu !');
            return $this->render('events/events.html.twig', [
                'formevent' => $form->createView(),
                'events' => $events,
                'new' => $new,
                'event' => $event
            ]);
        }else{
            return $this->render('events/events.html.twig', [
                'formevent' => $form->createView(),
                'events' => $events,
                'new' => $new,
                'event' => $event
            ]);
        }
    }
}


