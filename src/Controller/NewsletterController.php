<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\Knifes;
use App\Entity\Newsletter;
use App\Services\FileHandler;
use App\Services\MailO2;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/sendmailing/{mailingtype?null}/{objectname?null}', name: 'newsletter.send')]
    public function sendNewsletter(
        string $mailingtype,
        string $objectname,
        EntityManagerInterface $entityManager,
        MailO2 $mail,
        FileHandler $fileHandler
    )
    {
        $html =  'emails/event-announcement.html';
        $content = $fileHandler->getFileContent($html);
        if($mailingtype === 'knife'){
            $members = $entityManager->getRepository(Newsletter::class)->findBy(['forknife' =>  1]);
        }else{
            $members = $entityManager->getRepository(Newsletter::class)->findBy(['forevents' =>  1]);
        }
        $mailinglist = [];

        try {
            foreach($members as $member) {
                array_push($mailinglist, $member->getEmail());
                // $this->addFlash('success', "Email sent to ".$member->getEmail());
                $mail->sendEmail($_ENV['MAIL_FROM'], $member->getEmail(), "Marketing blast prototype", $content);
            }
            return $this->json([
                "message" => "Votre demande a été prise en compte pour $mailingtype / $objectname. Emails envoyés à ".count($mailinglist).
                " destinataires"
            ], 200);
        }
        catch(Exception $e) {
            // $this->addFlash('error', $e->getMessage())
            return $this->json([
                "message" => "Erreur : ".$e->getMessage()
            ], 400);
        }
    }

    #[Route('/subscribenewsletter', name: 'newsletter.subscribe')]
    public function subscribeNewsletter(
        Request $request, 
        EntityManagerInterface $entityManager,
        MailO2 $mail,
        FileHandler $fh
    ): Response
    {
        $data = file_get_contents("php://input");
        $payload = array();
        parse_str($data, $payload);
        $email = $payload['email'];
        try {
            $newsletter = new Newsletter();
            $kb = 0;
            $eb = 0;
            $subscribedevents = 'Aucun abonnement';
            if($payload['knifes'] == 'true'){
                $kb = 1;
                $subscribedevents = 'Abonné aux news sur les couteaux';
            }
            if($payload['events'] == 'true'){
                $eb = 1;
                if($kb) {
                    $subscribedevents .= ' et aux annonces de présence sur les salons.';
                }
                else {
                    $subscribedevents = ' Abonné aux annonces de présence sur les salons.';
                }
            }
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                $newsletter->setEmail($email)
                       ->setForknife($kb)
                       ->setForevents($eb);
                $entityManager->persist($newsletter);
                $entityManager->flush();
                // Inform administrator
                $html =  'emails/newsletter-member-added.html';
                $content = $fh->getFileContent($html);
                $content = str_replace('{useremail}', $email, $content);
                $content = str_replace('{registertypemessage}', $subscribedevents, $content);
                // $mail->sendEmail($_ENV['MAIL_FROM'],$_ENV['MAIL_ADMIN'], "New user registered ", $content);
                return $this->json([
                    "message" => "Votre demande a été prise en compte pour $email"
                ], 200);
            }else{
                return $this->json([
                    "message" => "Merci de saisir un email valide : $email"
                ], 400);
            }
        }
        catch(Exception $e) {
            return $this->json([
                "message" => "Erreur : ".$e->getMessage(),
                "data" =>$data
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


