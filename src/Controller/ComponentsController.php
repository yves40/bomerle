<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Mechanism;
use App\Entity\Metals;
use App\Form\CategoryType;
use App\Form\MetalsType;
use App\Form\MechanismType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/components')]
class ComponentsController extends AbstractController
{
    #[Route('/index', name: 'index_components')]
    public function index(
        Category $category = null,
        EntityManagerInterface $entityManager
    ): Response
    {
        $category = new Category();
        $metal = new Metals();
        $mechanism = new Mechanism();
        $categories = $entityManager->getRepository(Category::class)->listCategories();
        $metals = $entityManager->getRepository(Metals::class)->listMetals();
        $mechanisms = $entityManager->getRepository(Mechanism::class)->listMechanisms();
        $formCategory = $this->createForm(CategoryType::class, $category, [
            'action' => $this->generateUrl('category.add'),
            'method' => 'POST',
        ]);
        $formMetals = $this->createForm(MetalsType::class, $metal, [
            'action' => $this->generateUrl('metal.add'),
            'method' => 'POST',
        ]);
        $formMechanism = $this->createForm(MechanismType::class, $mechanism, [
            'action' => $this->generateUrl('mechanism.add'),
            'method' => 'POST',
        ]);
        return $this->render('components/dashboard.html.twig', [
            'formcategory' => $formCategory->createView(),
            'category' => $category,
            'categories' => $categories,
            'formmetals' => $formMetals->createView(),
            'metal' => $metal,
            'metals' => $metals,
            'formmechanism' => $formMechanism->createView(),
            'mechanism' => $mechanism,
            'mechanisms' => $mechanisms
        ]);        
    }

    #[Route('/addcategory', name: 'category.add')]
    public function addCategory(
        Category $category = null,
        EntityManagerInterface $entityManager
    ): Response
    {
        $category = new Category();
        $category->setName(($_POST['category'])['name']);
        $entityManager->persist($category);
        $entityManager->flush();
        $category->setName('');

        return $this->redirectToRoute('index_components');
    }
    #[Route('/addmetal', name: 'metal.add')]
    public function addMetal(
        Metals $metal = null,
        EntityManagerInterface $entityManager
    ): Response
    {
        $metal = new Metals();
        $metal->setName(($_POST['metals'])['name']);
        $entityManager->persist($metal);
        $entityManager->flush();
        $metal->setName('');

        return $this->redirectToRoute('index_components');
    }
    #[Route('/addmechanism', name: 'mechanism.add')]
    public function addMechanism(
        Mechanism $mechanism = null,
        EntityManagerInterface $entityManager
    ): Response
    {
        $mechanism = new Mechanism();
        $mechanism->setName(($_POST['mechanism'])['name']);
        $entityManager->persist($mechanism);
        $entityManager->flush();
        $mechanism->setName('');
        return $this->redirectToRoute('index_components');
    }
}
