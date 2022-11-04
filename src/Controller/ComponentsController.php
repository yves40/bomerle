<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/components')]
class ComponentsController extends AbstractController
{
    #[Route('/index', name: 'index_components')]
    public function index(
        Category $category = null,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $category = new Category();
        $formCategory = $this->createForm(CategoryType::class, $category);
        $formCategory->handleRequest($request);
        $categories = $entityManager->getRepository(Category::class)->listCategories();
        // dd($categories);
        if($formCategory->isSubmitted()){
            
            $entityManager->persist($category);
            $entityManager->flush();
            $category->setName('');
            $formCategory = $this->createForm(CategoryType::class, $category);
            return $this->render('components/dashboard.html.twig', [
                'formcategory' => $formCategory->createView(),
                'category' => $category,
                'categories' => $categories
            ]);
        }else{
            return $this->render('components/dashboard.html.twig', [
                'formcategory' => $formCategory->createView(),
                'category' => $category,
                'categories' => $categories
            ]);
        }
        
    }

    #[Route('/addcategory', name: 'category.add')]
    public function addCategory(): Response
    {
        return $this->render('components/dashboard.html.twig', [
            'controller_name' => 'ComponentsController',
        ]);
    }
}
