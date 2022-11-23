<?php

namespace App\Controller;

use App\Entity\Knifes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gallery')]
class GalleryController extends AbstractController
{
    #[Route('/allknifes', name: 'gallery.home')]
    public function index(
        EntityManagerInterface $entityManager
    ): Response
    {
        $knifes = $entityManager->getRepository(Knifes::class)->findBy(
            [],
            ['id' => 'DESC']
        );
        // dd($knifes);
        return $this->render('gallery/allknifes.html.twig', [
            'knifes' => $knifes,
        ]);
    }
}
