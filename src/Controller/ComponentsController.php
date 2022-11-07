<?php

namespace App\Controller;

use App\Entity\Accessories;
use App\Entity\Category;
use App\Entity\Handle;
use App\Entity\Knifes;
use App\Entity\Mechanism;
use App\Entity\Metals;
use App\Form\AccessoriesType;
use App\Form\CategoryType;
use App\Form\HandleType;
use App\Form\KnifesType;
use App\Form\MechanismType;
use App\Form\MetalsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/components')]
class ComponentsController extends AbstractController
{
    #[Route('/index', name: 'index_components')]
    public function index(
        EntityManagerInterface $entityManager
    ): Response
    {
        $category = new Category();
        $metal = new Metals();
        $mechanism = new Mechanism();
        $accessory = new Accessories();
        $handle = new Handle();
        $knife = new Knifes();
        $categories = $entityManager->getRepository(Category::class)->listCategories();
        $metals = $entityManager->getRepository(Metals::class)->listMetals();
        $mechanisms = $entityManager->getRepository(Mechanism::class)->listMechanisms();
        $accessories = $entityManager->getRepository(Accessories::class)->listAccessories();
        $handles = $entityManager->getRepository(Handle::class)->listHandles();
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
        $formAccessories = $this->createForm(AccessoriesType::class, $accessory, [
            'action' => $this->generateUrl('accessory.add'),
            'method' => 'POST',
        ]);
        $formHandle = $this->createForm(HandleType::class, $handle, [
            'action' => $this->generateUrl('handle.add'),
            'method' => 'POST',
        ]);
        $formKnifes = $this->createForm(KnifesType::class, $knife, [
            'action' =>$this->generateUrl('knife.add'),
            'method' => 'POST',
        ]);
        // dd($knife);
        return $this->render('components/dashboard.html.twig', [
            'formcategory' => $formCategory->createView(),
            'category' => $category,
            'categories' => $categories,
            'formmetals' => $formMetals->createView(),
            'metal' => $metal,
            'metals' => $metals,
            'formmechanism' => $formMechanism->createView(),
            'mechanism' => $mechanism,
            'mechanisms' => $mechanisms,
            'formaccessories' => $formAccessories->createView(),
            'accessory' => $accessory,
            'accessories' => $accessories,
            'formhandle' => $formHandle->createView(),
            'handle' => $handle,
            'handles' => $handles,
            'formknifes' => $formKnifes->createView(),
            'knife' => $knife
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
    #[Route('/addaccessory', name: 'accessory.add')]
    public function addAccessory(
        Accessories $accessory = null,
        EntityManagerInterface $entityManager
    ): Response
    {
        $accessory = new Accessories();
        $accessory->setName(($_POST['accessories'])['name']);
        $entityManager->persist($accessory);
        $entityManager->flush();
        $accessory->setName('');
        return $this->redirectToRoute('index_components');
    }
    #[Route('/addhandle', name: 'handle.add')]
    public function addHandle(
        Handle $handle = null,
        EntityManagerInterface $entityManager
    ): Response
    {
        $handle = new Handle();
        $handle->setName(($_POST['handle'])['name']);
        $entityManager->persist($handle);
        $entityManager->flush();
        $handle->setName('');
        return $this->redirectToRoute('index_components');
    }
    #[Route('/addknife', name: 'knife.add')]
    public function addKnife(
        Knifes $knife = null,
        EntityManagerInterface $entityManager
    ): Response
    {
        $knife = new Knifes();
        // $category = new Category();
        // $category->setName(($_POST['knifes'])['category']);
        $knife->setName(($_POST['knifes'])['name'])
              ->setDescription(($_POST['knifes'])['description'])
              ->setStock(($_POST['knifes'])['stock'])
              ->setWeight(($_POST['knifes'])['weight'])
              ->setLenght(($_POST['knifes'])['lenght'])
              ->setCloseLenght(($_POST['knifes'])['close_lenght'])
              ->setCuttingedgeLenght(($_POST['knifes'])['cuttingedge_lenght'])
              ->setPrice(($_POST['knifes'])['price']);
        // $knife->setCategory($category);
        
        // $entityManager->persist($category); // pas une bonne idée
        $entityManager->persist($knife);
        $entityManager->flush();
        $knife->setName('');
        return $this->redirectToRoute('index_components');
    }
}
