<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\Knifes;
use App\Entity\Newsletter;
use App\Services\MailO2;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{
    #[Route('/newsletter', name: 'newsletter.prepare')]
    public function generateNewsletter(
        EntityManagerInterface $entityManager
    ): Response
    {
        $events = $entityManager->getRepository(Events::class)->listEvents();
        $knifes = $entityManager->getRepository(Knifes::class)->findBy([], ['id' => 'DESC']);
        return $this->render('newsletter/generate.html.twig', [
            'events' => $events,
            'knifes' => $knifes
        ]);
    }
    #[Route('/newsletter/{mailingtype?null}/{objectname?null}', name: 'newsletter.send')]
    public function sendNewsletter(
        string $mailingtype,
        string $objectname,
        EntityManagerInterface $entityManager
    ): Response
    {
        return $this->json([
            "message" => "Votre demande a été prise en compte pour $mailingtype / $objectname"
        ], 200);
    }

    #[Route('/subscribenewsletter/{email?null}/{knife?false}/{events?false}', name: 'newsletter.subscribe')]
    public function subscribeNewsletter(
        string $email,
        string $knife,
        string $events,
        EntityManagerInterface $entityManager,
        MailO2 $mail
    ): Response
    {
        $newsletter = new Newsletter();
        $kb = 0;
        $eb = 0;
        if($knife == 'true'){
            $kb = 1;
        }
        if($events == 'true'){
            $eb = 1;
        }
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $newsletter->setEmail($email)
                   ->setForknife($kb)
                   ->setForevents($eb);
            $entityManager->persist($newsletter);
            $entityManager->flush();
            $mail->sendAdministratorInfo(null, 'Abonnement Newsletter', "$email s'est inscrit");
            return $this->json([
                "message" => "Votre demande a été prise en compte"
            ], 200);
        }else{
            return $this->json([
                "message" => "Merci de saisir un email valide"
            ], 400);
        }
    }
    #[Route('/unsubscribenewsletter/{id}', name: 'newsletter.unsubscribe')]
    public function unsubscribeNewsletter(): Response
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        return $this->render('home.html.twig', [
            'formnewsletter' => $form->createView()
        ]);
    }
}


