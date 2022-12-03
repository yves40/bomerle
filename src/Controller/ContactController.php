<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Newsletter;
use App\Form\ContactType;
use App\Form\NewsletterType;
use App\Services\FileHandler;
use App\Services\MailO2;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contact')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'contact.classic')]
    public function index(
        EntityManagerInterface $entityManager,
        Request $request,
        MailO2 $mail,
        FileHandler $fh
    ): Response
    {
        $newsletter = new Newsletter;
        $contact = new Contact();
        $formNewsletter = $this->createForm(NewsletterType::class, $newsletter);
        $formNewsletter->handleRequest($request);
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);
        
        if($formContact->isSubmitted() && $formContact->isValid()){
            $email = $contact->getEmail();
            $object = $contact->getObject();
            $text = $contact->getText();
            if($contact->getReservation() !== null){
                $knife = $contact->getReservation()->getName();
            }else{
                $knife = "";
            }            
            $content = $fh->getFileContent('emails/contact-request.html');
            $content = str_replace('{email}', $email, $content);
            $content = str_replace('{object}', $object, $content);
            $content = str_replace('{text}', $text, $content);
            $content = str_replace('{knife}', $knife, $content);
            $mail->sendEmail($_ENV['MAIL_FROM'],$_ENV['MAIL_ADMIN'], "New resquest sent : $object", $content);
            $this->addFlash('success', 'Votre demande a été prise en compte');
            return $this->redirectToRoute('home');

        }else{
            return $this->render('contact/contact.html.twig', [
                'formnewsletter' => $formNewsletter->createView(),
                'formcontact' => $formContact->createView(),
            ]);
        }


    }
}
