<?php

namespace App\Controller;

use Exception;
use DateTime;

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

    // --------------------------------------------------------------------------
    const NEXTEVENTS = 1;
    const PREVIOUSEVENTS = 2;

    private LocaleSwitcher $localeSwitcher;
    // --------------------------------------------------------------------------
    public function __construct(LocaleSwitcher $localeSwitcher)
    {
        $this->localeSwitcher = $localeSwitcher;
    }
    // --------------------------------------------------------------------------
    //      E V E N T S    S E R V I C E S 
    // --------------------------------------------------------------------------
    // listall :    0 Yes
    //              1 Next events
    //              2 Previous events
    #[Route('/events/all/{new?true}/{id?0}/{listall?0}', name: 'bootadmin.events.all')]
    public function home(Request $request,
                        $new,
                        $id,
                        $listall,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        $repo = $entityManager->getRepository(Events::class);
        $now = new DateTime();
        $events = $repo->findFutureEvents($now);
        $eventsbefore = $repo->findPreviousEvents($now);
        $event = new Events();
        $form = $this->createForm(EventsType::class, $event);
        switch($new) {
            case "true":
                    $form->handleRequest($request);
                    if($form->isSubmitted() && ( $form->isValid())){
                        $entityManager->persist($event);
                        $entityManager->flush();
                        $events = $repo->findFutureEvents($now);
                        $eventsbefore = $repo->findPreviousEvents($now);
                        $event = new Events();
                        $this->addFlash('success', $translator->trans('admin.manageevents.created'));
                    }
                    $event->setDate($now);
                    break;
            case "abort":
                    $new = "true";
                    $event->setDate($now);
                    $this->addFlash('success', $translator->trans('genericmessages.cancel'));
                    break;
            case "false":
                    $event = $repo->findOneBy(['id' => $id]);
                    break;
        }
        return $this->render('admin/events.html.twig', [
                "form" => $form->createView(),
                "event" => $event,
                'listall' => $listall,
                "events" => $events,
                "eventsbefore" => $eventsbefore,
                "locale" =>  $loc,
                "new" => $new,
                "id" => $id
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
        // $loc = $this->locale($request);
        $repo = $entityManager->getRepository(Events::class);
        // $events = $repo->findAll();
        $event = $repo->findOneBy(['id' => $id]);
        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);
        if($form->isSubmitted() && ( $form->isValid())){
            $entityManager->persist($event);
            $entityManager->flush();
            $events = $repo->findAll();
            $event = new Events();
            $this->addFlash('success', $translator->trans('admin.manageevents.updated'));
            return $this->redirectToRoute('bootadmin.events.all', array( 'new' => "true"));
        }
        return $this->redirectToRoute('bootadmin.events.all', array( 'new' => "false",
                                                                        'id' => $id));
    }
    // --------------------------------------------------------------------------
    #[Route('/events/delete/{id}', name: 'bootadmin.events.delete')]
    public function deleteEvent(Request $request,
                        int $id,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        // $loc = $this->locale($request);
        $repo = $entityManager->getRepository(Events::class);
        $event = $repo->findOneBy(['id' => $id]);
        try {
            $entityManager->remove($event);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('admin.manageevents.deleted'));
        }
        catch(Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('bootadmin.events.all', array( 'new' => "true"));
    }
    // --------------------------------------------------------------------------
    #[Route('/events/allnext', name: 'bootadmin.events.allnext')]
    public function allNextEvents(Request $request,
                        TranslatorInterface $translator): Response
    {
        return $this->redirectToRoute('bootadmin.events.all', array( 'new' => "true",
                                                                        'id' => 0,
                                                                        'listall' => $this::NEXTEVENTS));
    }
    // --------------------------------------------------------------------------
    #[Route('/events/allprevious', name: 'bootadmin.events.allprevious')]
    public function allPreviousEvents(Request $request,
                        TranslatorInterface $translator): Response
    {
        return $this->redirectToRoute('bootadmin.events.all', array( 'new' => "true",
                                                                        'id' => 0,
                                                                        'listall' => $this::PREVIOUSEVENTS));
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
