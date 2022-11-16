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
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/components')]
class ComponentsController extends AbstractController
{
    #[Route('/index', name: 'index.dashboard')]
    public function index(): Response
    {
        return $this->render('components/dashboard.html.twig');               
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
            $this->addFlash('success', "La catégorie ".$category->getName()." a été ajoutée");
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
            $this->addFlash('success', "Le métal ".$metal->getName()." a été ajouté");
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
            $this->addFlash('success', "Le mécanisme ".$mechanism->getName()." a été ajouté");
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
            $this->addFlash('success', "L'accessoire ".$accessory->getName()." a été ajouté");
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
            $this->addFlash('success', "Le matériau ".$handle->getName()." a été ajouté");
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
        Uploader $uploader,
        ValidatorInterface $validator
    ): Response
    {
        ini_set('upload_max_filesize', 5);
        $knife = new Knifes();

        $form = $this->createForm(KnifesType::class, $knife);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // $errors = $validator->validate($form);
            // dd($errors);
            $uploadedFiles = $form->get('images')->getData();
            $physicalPath = $this->getParameter('knifeimages_directory');

            for($i=0; $i < count($uploadedFiles); $i++){
                $image = new Images();

                $newFileName = $uploader->uploadFile($uploadedFiles[$i], $physicalPath);
                $image->setFilename($newFileName);
                if($i == 0){
                    $image->setMainpicture(true);
                }else{
                    $image->setMainpicture(false);
                }
                $knife->addImage($image);
            }
            $entityManager->persist($knife);
            $entityManager->flush();
            $this->addFlash('success', "Le couteau ".$knife->getName()." a été ajouté");
            $knife = new Knifes();
            $form = $this->createForm(KnifesType::class, $knife);
            return $this->render('components/knifes.html.twig', [
                'formknifes' => $form->createView()
            ]);
        }elseif($form->isSubmitted() && !$form->isValid()){
            // dd($request);
            // dd($form->getErrors());
            // $errors = $validator->validate($form);
            // dd($errors);
            $this->addFlash('error', 'Un problème est survenu !');
            return $this->render('components/knifes.html.twig', [
                'formknifes' => $form->createView()
            ]);
        }else{
            return $this->render('components/knifes.html.twig', [
                'formknifes' => $form->createView()
            ]);
        }
    }
}
