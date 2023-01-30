<?php

namespace App\Events;

use App\Service\DBlogger;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/*
    For this to work, update services.yaml.
    Under the services: section. 

    App\Events\LogoutHandler:
    tags:
        - name: 'kernel.event_listener'
            event: 'Symfony\Component\Security\Http\Event\LogoutEvent'
            dispatcher: security.event_dispatcher.main
*/

class LogoutHandler implements EventSubscriberInterface
{

    private $dblogger;

    // -----------------------------------------------------------------------------------------------------------
    public function __construct( private UrlGeneratorInterface $urlGenerator,
                                    private ManagerRegistry $mgr
                                ) 
    {
        $this->dblogger = new DBlogger($this->mgr);
    }
    // -----------------------------------------------------------------------------------------------------------
    public static function getSubscribedEvents(): array
    {
        return [LogoutEvent::class => 'onLogout'];
    }
    // -----------------------------------------------------------------------------------------------------------
    public function onLogout(LogoutEvent $event): void
    {
        // get the security token of the session that is about to be logged out
        $token = $event->getToken();
        // get the current request
        $request = $event->getRequest();
        // dd($token);
        $email = $token->getUser()->getEmail();

        // get the current response, if it is already set by another listener
        $response = $event->getResponse();

        // configure a custom logout response to the homepage
        $response = new RedirectResponse(
            $this->urlGenerator->generate('home'),
            RedirectResponse::HTTP_SEE_OTHER
        );
        $this->dblogger->info($email . ' logged out');
        $event->setResponse($response);
    }
}