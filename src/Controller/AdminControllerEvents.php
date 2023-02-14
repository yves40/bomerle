<?php

namespace App\Controller;

use Error;
use Exception;

use App\Entity\Events;
use App\Form\EventsType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/bootadmin')]
class AdminControllerEvents extends AbstractController
{
    private LocaleSwitcher $localeSwitcher;
    // --------------------------------------------------------------------------
    public function __construct(LocaleSwitcher $localeSwitcher)
    {
        $this->localeSwitcher = $localeSwitcher;
    }
    // --------------------------------------------------------------------------
    //      E V E N T S    S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/events/all/{new?true}', name: 'bootadmin.events.all')]
    public function home(Request $request,
                        bool $new,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        $loc = $this->locale($request);
        $repo = $entityManager->getRepository(Events::class);
        $events = $repo->findAll();
        $event = new Events();
        $form = $this->createForm(EventsType::class, $event);
        if($new === 'abort') {
            $new = true;
        }
        else {
            $form->handleRequest($request);
            if($form->isSubmitted() && ( $form->isValid())){
                $entityManager->persist($event);
                $entityManager->flush();
                $events = $repo->findAll();
                $event = new Events();
                $this->addFlash('success', $translator->trans('admin.manageevents.created'));
            }
        }
        return $this->render('admin/events.html.twig', [
                "form" => $form->createView(),
                "event" => $event,
                "eventslist" => $events,
                "locale" =>  $loc,
                "new" => $new
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/events/update/{id}', name: 'bootadmin.events.update')]
    public function updateEvent(Request $request,
                        int $id,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        $loc = $this->locale($request);
        $repo = $entityManager->getRepository(Events::class);
        $events = $repo->findAll();
        $event = $repo->findOneBy(['id' => $id]);
        $form = $this->createForm(EventsType::class, $event);
        return $this->render('admin/events.html.twig', [
                "form" => $form->createView(),
                "eventslist" => $events,
                "event" => $event,
                "locale" =>  $loc,
                "new" => false,
                "id" => $id
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/events/delete/{id}', name: 'bootadmin.events.delete')]
    public function deleteEvent(Request $request,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        $loc = $this->locale($request);
        $repo = $entityManager->getRepository(Events::class);
        $events = $repo->findAll();
        $event = new Events();
        $form = $this->createForm(EventsType::class, $event);
        return $this->render('admin/events.html.twig', [
                "form" => $form->createView(),
                "eventslist" => $events,
                "locale" =>  $loc,
                "new" => true
            ]
        );               
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
