<?php

namespace App\Controller;

use App\Entity\Knifes;
use App\Entity\Newsletter;
use App\Form\ContactType;
use App\Form\NewsletterType;
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
        Request $request
    ): Response
    {
        $newsletter = new Newsletter;
        $knife = new Knifes();
        
        $knifes = $entityManager->getRepository(Knifes::class)->findAll();
        $formNewsletter = $this->createForm(NewsletterType::class, $newsletter);
        $formNewsletter->handleRequest($request);
        $formContact = $this->createForm(ContactType::class);
        
        return $this->render('contact/contact.html.twig', [
            'formnewsletter' => $formNewsletter->createView(),
            'formcontact' => $formContact,
            'knifes' => $knifes
        ]);
    }
}
