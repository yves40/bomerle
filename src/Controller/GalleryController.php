<?php

namespace App\Controller;

use App\Entity\Knifes;
use App\Entity\Newsletter;
use App\Form\NewsletterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gallery')]
class GalleryController extends AbstractController
{
    #[Route('/allknifes', name: 'gallery.home')]
    public function index(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $knifes = $entityManager->getRepository(Knifes::class)->findBy(
            [],
            ['id' => 'DESC']
        );
        $newsletter = new Newsletter;
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);
        // dd($knifes);
        return $this->render('gallery/allknifes.html.twig', [
            'knifes' => $knifes,
            'formnewsletter' => $form->createView()
        ]);
    }

    #[Route('/detail/{id}', name: 'gallery.detail')]
    public function knifeDetail(
        int $id,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $knife = $entityManager->getRepository(Knifes::class)->findOneBy(['id' => $id]);
        $newsletter = new Newsletter;
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);
        return $this->render('gallery/knifedetail.html.twig', [
            'knife' => $knife,
            'formnewsletter' => $form->createView()
        ]);
    }
}
