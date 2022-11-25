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

    #[Route('/detail/{id}', name: 'gallery.detail')]
    public function knifeDetail(
        int $id,
        EntityManagerInterface $entityManager
    ): Response
    {
        $knife = $entityManager->getRepository(Knifes::class)->findOneBy(['id' => $id]);
        // dd($knife);
        return $this->render('gallery/knifedetail.html.twig', [
            'knife' => $knife
        ]);
    }
}
