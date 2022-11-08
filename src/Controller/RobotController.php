<?php

namespace App\Controller;

use DateTime;
use App\Entity\RequestsTracker;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RequestsTrackerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/robot')]
class RobotController extends AbstractController
{      
    #[Route('/registeruserconfirmed/{selector}/{token}', 
            name: 'user.register')]
    public function registerUser(string $selector, 
                                string $token, 
                                EntityManagerInterface $entityManager,
                                ): Response
    {
        /* @$rqtr RequestsTrackerRepository */
        $rqtr = $entityManager->getRepository(RequestsTracker::class);
        $userrequest = $rqtr->findRequestBySelector($selector);
        // Any match ??
        if(!empty($userrequest)) {
            $userrequest = $rqtr->findRequestBySelector($selector)[0];
            if($userrequest->getToken() === $token) {
                // Now check the expiration date
                $currentdate = date("U");
                if($currentdate <= $userrequest->getExpires()) {
                    $userrequest->setStatus(RequestsTracker::STATUS_PROCESSED);
                    $page = "<p>Request found for Selector $selector</p>".
                        "<p>Token is verified: $token</p>".
                        "<p>Expiration date in delay, you can connect with your new identity</p>";
                }
                else {
                    $userrequest->setStatus(RequestsTracker::STATUS_EXPIRED);
                    $atlast = date('d-m-Y h:i',$userrequest->getExpires());
                    $page = "<p>Request found for Selector $selector</p>".
                        "<p>Token is verified: $token</p>".
                        "<p>Expiration date is outdated. You should have answered before $atlast</p>";
                }
            }
            else {
                $userrequest->setStatus(RequestsTracker::STATUS_REJECTED);
                $page = "<p>Request found for Selector $selector</p>".
                    "<p>But Token is not the good one: $token</p>";
            }
            date_default_timezone_set('Europe/Paris');
            $userrequest->setProcessed(new DateTime('now'));
            $entityManager->persist($userrequest);  // Update tracking table
            $entityManager->flush();
        }
        else {
            $page = "<p>Unknown request for Selector $selector</p>".
                                "<p>Token is : $token</p>";
        }
        return new Response($page);
    }
}
