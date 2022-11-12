<?php

namespace App\Controller;

use App\Entity\Accessories;
use App\Entity\Category;
use App\Entity\Handle;
use App\Entity\Images;
use App\Entity\Knifes;
use App\Entity\Mechanism;
use App\Entity\Metals;
use App\Form\AccessoriesType;
use App\Form\CategoryType;
use App\Form\HandleType;
use App\Form\KnifesType;
use App\Form\MechanismType;
use App\Form\MetalsType;
use App\Services\Uploader;
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
        EntityManagerInterface $entityManager,
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
            'knife' => $knife,
        ]);               
    }

    #[Route('/addcategory', name: 'category.add')]
    public function addCategory(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $category = new Category();
        $cater = $entityManager->getRepository(Category::class);
        $categories = $cater->listCategories();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();
            $categories = $cater->listCategories();
            $this->addFlash('success', $category->getName());
            $category = new Category();
            $form = $this->createForm(CategoryType::class, $category);
            return $this->render('components/category.html.twig', [
                'formcategory' => $form->createView(),
                'categories' => $categories
            ]);
        }elseif($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('error', 'Un problème est survenu !');
            return $this->render('components/category.html.twig', [
                'formcategory' => $form->createView(),
                'categories' => $categories
            ]);
        }else{
            return $this->render('components/category.html.twig', [
                'formcategory' => $form->createView(),
                'categories' => $categories
            ]);
        }
    }
    #[Route('/addmetal', name: 'metal.add')]
    public function addMetal(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $metal = new Metals();
        $met = $entityManager->getRepository(Metals::class);
        $metals = $met->listMetals();
        $form = $this->createForm(MetalsType::class, $metal);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){    
            $entityManager->persist($metal);
            $entityManager->flush();
            $metals = $met->listMetals();
            $this->addFlash('success', $metal->getName());
            $metal = new Metals();
            $form = $this->createForm(MetalsType::class, $metal);
            return $this->render('components/metals.html.twig', [
                'formmetals' => $form->createView(),
                'metals' => $metals
            ]);
        }elseif($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('error', 'Un problème est survenu !');
            return $this->render('components/metals.html.twig', [
                'formmetals' => $form->createView(),
                'metals' => $metals
            ]);
        }else{
            return $this->render('components/metals.html.twig', [
                'formmetals' => $form->createView(),
                'metals' => $metals
            ]);
        }
    }
    #[Route('/addmechanism', name: 'mechanism.add')]
    public function addMechanism(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $mechanism = new Mechanism();
        $mecha = $entityManager->getRepository(Mechanism::class);
        $mechanisms = $mecha->listMechanisms();
        $form = $this->createForm(MechanismType::class, $mechanism);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($mechanism);
            $entityManager->flush();
            $mechanisms = $mecha->listMechanisms();
            $this->addFlash('success', $mechanism->getName());
            $mechanism = new Mechanism();
            $form = $this->createForm(MechanismType::class, $mechanism);
            return $this->render('components/mechanism.html.twig', [
                'formmechanism' => $form->createView(),
                'mechanisms' => $mechanisms
            ]);
        }elseif($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('error', 'Un problème est survenu !');
            return $this->render('components/mechanism.html.twig', [
                'formmechanism' => $form->createView(),
                'mechanisms' => $mechanisms
            ]);
        }else{
            return $this->render('components/mechanism.html.twig', [
                'formmechanism' => $form->createView(),
                'mechanisms' => $mechanisms
            ]);
        }
    }
    #[Route('/addaccessory', name: 'accessory.add')]
    public function addAccessory(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $accessory = new Accessories();
        $acc = $entityManager->getRepository(Accessories::class);
        $accessories = $acc->listAccessories();
        $form = $this->createForm(AccessoriesType::class, $accessory);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($accessory);
            $entityManager->flush();
            $accessories = $acc->listAccessories();
            $this->addFlash('success', $accessory->getName());
            $accessory = new Accessories();
            $form = $this->createForm(AccessoriesType::class, $accessory);
            return $this->render('components/accessories.html.twig', [
                'formaccessories' => $form->createView(),
                'accessories' => $accessories
            ]);
        }elseif($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('error', 'Un problème est survenu !');
            return $this->render('components/accessories.html.twig', [
                'formaccessories' => $form->createView(),
                'accessories' => $accessories
            ]);
        }else{
            return $this->render('components/accessories.html.twig', [
                'formaccessories' => $form->createView(),
                'accessories' => $accessories
            ]);
        }
    }
    #[Route('/addhandle', name: 'handle.add')]
    public function addHandle(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $handle = new Handle();
        $han = $entityManager->getRepository(Handle::class);
        $handles = $han->listHandles();
        $form = $this->createForm(HandleType::class, $handle);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($handle);
            $entityManager->flush();
            $handles = $han->listHandles();
            $this->addFlash('success', $handle->getName());
            $handle = new Handle();
            $form = $this->createForm(HandleType::class, $handle);
            return $this->render('components/handle.html.twig', [
                'formhandle' => $form->createView(),
                'handles' => $handles
            ]);
        }elseif($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('error', 'Un problème est survenu !');
            return $this->render('components/handle.html.twig', [
                'formhandle' => $form->createView(),
                'handles' => $handles
            ]);
        }else{
            return $this->render('components/handle.html.twig', [
                'formhandle' => $form->createView(),
                'handles' => $handles
            ]);
        }
        
        $handle->setName('');
        return $this->redirectToRoute('index_components');
    }
    #[Route('/addknife', name: 'knife.add')]
    public function addKnife(
        Request $request,
        EntityManagerInterface $entityManager,
        Uploader $uploader
    ): Response
    {
        $knife = new Knifes();
        $form = $this->createForm(KnifesType::class, $knife);
        $form->handleRequest($request);
        $uploadedFiles = $form->get('images')->getData();
        $physicalPath = $this->getParameter('knifeimages_directory');
        $error = false;
        for($i=0; $i < count($uploadedFiles); $i++){
            if($uploadedFiles[$i] && $uploadedFiles[$i]->getError() === UPLOAD_ERR_OK){
                $image = new Images();
                $newFileName = $uploader->uploadFile($uploadedFiles[$i], $physicalPath);
                // dd($newFileName);
                $image->setFilename($newFileName);
                if($i == 0){
                    $image->setMainpicture(true);
                }else{
                    $image->setMainpicture(false);
                }
                $knife->addImage($image);    
            }else{
                $errorMessage = $uploader->getErrorMessage($uploadedFiles[$i]);
                $error = true;     
            }
        }
        if(!$error){    
            $entityManager->persist($knife);
            $entityManager->flush();
        }
        return $this->redirectToRoute('index_components');
    }
}
