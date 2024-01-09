<?php

namespace App\Controller;

use Exception;

use App\Entity\Handle;
use App\Entity\Metals;
use App\Entity\Category;
use App\Form\HandleType;
use App\Form\MetalsType;

use App\Entity\Mechanism;
use App\Form\CategoryType;
use App\Services\Uploader;
use App\Entity\Accessories;
use App\Form\MechanismType;

use App\Form\AccessoriesType;

use App\Services\FileHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/bootadmin')]
class AdminController extends AbstractController
{
    private LocaleSwitcher $localeSwitcher;
    // --------------------------------------------------------------------------
    public function __construct(LocaleSwitcher $localeSwitcher)
    {
        $this->localeSwitcher = $localeSwitcher;
    }
    // --------------------------------------------------------------------------
    #[Route('/home', name: 'bootadmin.home')]
    public function home(Request $request): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        $fh = new FileHandler();
        $content = $fh->getFileContent('templates/'.$loc.'/adminhome.html');
        return $this->render('admin/boothome.html.twig', [
            "locale" =>  $this->localeSwitcher->getLocale(),
            "page" => $content
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/public', name: 'bootadmin.public')]
    public function public(Request $request): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        return $this->render('main.html.twig', [
            "locale" =>  $this->localeSwitcher->getLocale(),
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/switch/{locale}', name: 'bootadmin.switch')]
    public function switch(Request $request, string $locale): Response
    {
        $request->getSession()->set('bootadmin.lang', $locale);
        $this->localeSwitcher->setLocale($locale);
        $fh = new FileHandler();
        $content = $fh->getFileContent('templates/'.$locale.'/adminhome.html');
        return $this->render('admin/boothome.html.twig', [
            "locale" =>  $this->localeSwitcher->getLocale(),
            "page" => $content
            ]
        );               
    }
    // --------------------------------------------------------------------------
    // M E T A L S     S E R V I C E S 
    // The generic handler is used for all CRUD ops and render the page with 
    // a form and the metals list.
    // The render page is located in template/admin/metals.html.twig
    // --------------------------------------------------------------------------
    /*
        @param string $new  true : if creating a metal or displaying metals list
                            false: When updating a metal namespace
                            abort: When canceling a modification
        @param string $metalname contains the metal name when updating it
        @param number $id used when updating or deleting a metal
        @return Response
    */
    #[Route('/metals/home/{new?true}/{id?0}/{knifeconflict?0}', name: 'bootadmin.metals')]
    public function AdminMetals(Request $request,
                                $new,
                                $id,
                                $knifeconflict,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations

        $metal = new Metals();
        $repo = $entityManager->getRepository(Metals::class);
        if($id != 0 ) { // Edit existing metal ? 
            $metal = $repo->findOneBy([ 'id' => $id ]);
        }
        $metals = $repo->listMetals();
        $form = $this->createForm(MetalsType::class, $metal);
        if($new === 'abort') {  // The user aborted the modification
            $new = "true";
        }
        else {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $entityManager->persist($metal);
                $entityManager->flush();
                $metals = $repo->listMetals();
                $this->addFlash('success', $translator->trans('admin.managemetals.created'));
            }
        }
        return $this->render('admin/metals.html.twig', [
            "locale" =>  $loc,
            "new" =>  $new,
            "metalname" => $metal->getName(),
            "id" => $id,
            "knifeconflict" => $knifeconflict,
            "metals" => $metals,
            "form" => $form->createView()
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/metals/delete/{id}', name: 'bootadmin.metals.delete')]
    public function DeleteMetal(Request $request,
                                int $id,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        // Search for the selected metal to be deleted
        $repo = $entityManager->getRepository(Metals::class);
        $metal = $repo->find($id);
        $knifes = $metal->getKnifes();
        // Is this metal related to any knife ?
        if($knifes->count() > 0){
            $conflicts = array();
            foreach($knifes as $violation) { array_push($conflicts, ['name' => $violation->getName(), 'id' => $violation->getId()]);}
            $notice = $metal->getName().' : '.
                        $translator->trans('admin.managemetals.isused');
            $notice = $notice.' : '.$conflicts[0]['name'];
            $this->addFlash('error', $notice);
            return $this->redirectToRoute('bootadmin.metals', array( 'new' => "true", 
                                                                'id' => $metal->getId(),
                                                                'knifeconflict' => $conflicts[0]['id']));
        }
        $repo->remove($metal, true);
        $this->addFlash('success', $translator->trans('admin.managemetals.deleted'));
        return $this->redirectToRoute('bootadmin.metals', array( 'new' => "true"));
    }
    // --------------------------------------------------------------------------
    #[Route('/metals/update/{id}', name: 'bootadmin.metals.update')]
    public function UpdateMetal(Request $request,
                                int $id,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        // Search for the selected metal to be deleted
        $repo = $entityManager->getRepository(Metals::class);
        $metal = $repo->find($id);
        $form = $this->createForm(MetalsType::class, $metal);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($metal);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('admin.managemetals.updated'));
            return $this->redirectToRoute('bootadmin.metals', array( 'new' => "true"));
        }
        return $this->redirectToRoute('bootadmin.metals', 
                            array(  'new' => "false", 
                                    'id' => $metal->getId()
                                ));
    }
    // --------------------------------------------------------------------------
    // M E C H A N I S M S     S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/mechanisms/home/{new?true}/{id?0}/{knifeconflict?0}', name: 'bootadmin.mechanisms')]
    public function AdminMechanisms(Request $request,
                                $new,
                                $id,
                                $knifeconflict,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        /** 
         * @var Mechanism $mechanism 
         * @var MechanismRepository $repo
         * 
        */
        $mechanism = new Mechanism();
        $repo = $entityManager->getRepository(Mechanism::class);
        if ( $id != 0) {
            $mechanism = $repo->find($id);
        }
        $mechanisms = $repo->listMechanisms();
        $form = $this->createForm(MechanismType::class, $mechanism);
        if($new === 'abort') {  // The user aborted the modification
            $new = "true";
        }
        else {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $entityManager->persist($mechanism);
                $entityManager->flush();
                $mechanisms = $repo->listMechanisms();
                $this->addFlash('success', $translator->trans('admin.managemechanisms.created'));
            }
        }
        return $this->render('admin/mechanisms.html.twig', [
            "locale" =>  $loc,
            "new" =>  $new,
            "mechanismname" => $mechanism->getName(),
            "id" => $id,
            "knifeconflict" => $knifeconflict,
            "mechanisms" => $mechanisms,
            "form" => $form->createView()
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/mechanisms/delete/{id}', name: 'bootadmin.mechanisms.delete')]
    public function DeleteMechanism(Request $request,
                                int $id,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        // Search for the selected metal to be deleted
        $repo = $entityManager->getRepository(mechanism::class);
        $mechanism = $repo->find($id);
        $knifes = $mechanism->getKnifes();
        // Is this metal related to any knife ?
        if($knifes->count() > 0){
            $conflicts = array();
            foreach($knifes as $violation) { array_push($conflicts, ['name' => $violation->getName(), 'id' => $violation->getId()]);}
            $notice = $mechanism->getName() . ' : ' . 
                $translator->trans('admin.managemechanisms.isused');
                $notice = $notice.' : '.$conflicts[0]['name'];
                $this->addFlash('error', $notice);
            return $this->redirectToRoute('bootadmin.mechanisms', array( 'new' => "true",
                                                                 'id' => $mechanism->getId(),
                                                                 'knifeconflict' => $conflicts[0]['id']));
        }
        $repo->remove($mechanism, true);
        $this->addFlash('success', $translator->trans('admin.managemechanisms.deleted'));
        return $this->redirectToRoute('bootadmin.mechanisms', array( 'new' => "true"));
    }
    // --------------------------------------------------------------------------
    #[Route('/mechanisms/update/{id}', name: 'bootadmin.mechanisms.update')]
    public function UpdateMechanism(Request $request,
                                int $id,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        // Search for the selected metal to be deleted
        $repo = $entityManager->getRepository(Mechanism::class);
        $mechanism = $repo->find($id);
        $form = $this->createForm(MechanismType::class, $mechanism);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($mechanism);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('admin.managemechanisms.updated'));
            return $this->redirectToRoute('bootadmin.mechanisms', array( 'new' => "true"));
        }
        // var_dump($metal->getName(), $metal->getId());die;
        return $this->redirectToRoute('bootadmin.mechanisms', 
                            array(  'new' => "false", 
                                    'id' => $mechanism->getId()
                                ));
    }
    // --------------------------------------------------------------------------
    // H A N D L E S     S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/handles/home/{new?true}/{id?0}/{knifeconflict?0}', name: 'bootadmin.handles')]
    public function AdminHandles(Request $request,
                                $new,
                                $id,
                                $knifeconflict,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        $handle = new Handle();
        $repo = $entityManager->getRepository(Handle::class);
        if($id != 0) {  // Exisiting handle ?
            $handle = $repo->find($id);
        }
        $handles = $repo->listHandles();
        $form = $this->createForm(HandleType::class, $handle);
        if($new === 'abort') {  // The user aborted the modification
            $new = "true";
        }
        else {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $entityManager->persist($handle);
                $entityManager->flush();
                $handles = $repo->listHandles();
                $this->addFlash('success', $translator->trans('admin.managehandles.created'));
            }
        }
        return $this->render('admin/handles.html.twig', [
            "locale" =>  $loc,
            "new" =>  $new,
            "handlename" => $handle->getName(),
            "id" => $id,
            "knifeconflict" => $knifeconflict,
            "handles" => $handles,
            "form" => $form->createView()
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/handles/delete/{id}', name: 'bootadmin.handles.delete')]
    public function Deletehandle(Request $request,
                                int $id,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        // Search for the selected metal to be deleted
        $repo = $entityManager->getRepository(Handle::class);
        $handle = $repo->find($id);
        $knifes = $handle->getKnifes();
        // Is this metal related to any knife ?
        if($knifes->count() > 0){
            $conflicts = array();
            foreach($knifes as $violation) { array_push($conflicts, ['name' => $violation->getName(), 'id' => $violation->getId()]);}
            $notice =   $handle->getName() . ' : ' .
                        $translator->trans('admin.managehandles.isused');
            $notice = $notice.' : '.$conflicts[0]['name'];
            $this->addFlash('error', $notice);
            return $this->redirectToRoute('bootadmin.handles', array( 'new' => "true",
                                                                'id' => $handle->getId(),
                                                                'knifeconflict' => $conflicts[0]['id']));
        }
        $repo->remove($handle, true);
        $this->addFlash('success', $translator->trans('admin.managehandles.deleted'));        
        return $this->redirectToRoute('bootadmin.handles', array(  'new' => "true" ));
    }
    // --------------------------------------------------------------------------
    #[Route('/handles/update/{id}', name: 'bootadmin.handles.update')]
    public function Updatehandle(Request $request,
                                int $id,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        // Search for the selected metal to be deleted
        $repo = $entityManager->getRepository(Handle::class);
        $handle = $repo->find($id);
        $form = $this->createForm(HandleType::class, $handle);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($handle);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('admin.managehandles.updated'));
            return $this->redirectToRoute('bootadmin.handles', array( 'new' => "true"));
        }        
        return $this->redirectToRoute('bootadmin.handles', array(  'new' => "false",
                                                                'id' => $handle->getId()
                                                            ));
    }
    // --------------------------------------------------------------------------
    // C A T E G O R I E S     S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/categories/home/{new?true}/{id?0}/{knifeconflict?0}', name: 'bootadmin.categories')]
    public function AdminCategories(Request $request,
                                $new,
                                $id,
                                $knifeconflict,
                                Uploader $uploader,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {         
        $loc = $this->locale($request); // Set the proper language for translations
        /** 
         * @var Category $category 
         * @var CategoryRepository $repo
         * 
        */
        $category = new Category();
        $repo = $entityManager->getRepository(Category::class);
        if($id != 0){ // Exisiting Category ? 
            $category = $repo->find($id);
        }
        $categories = $repo->listCategories($new, $id);
        $form = $this->createForm(CategoryType::class, $category);
        if($new === 'abort') {  // The user aborted the modification
            $new = "true";
        }
        else {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                // Take care of image
                $uploaded = $form->get('image')->getData();
                $physicalPath = $this->getParameter('categoryimages_directory');
                if($uploaded !== null) {
                    $newFileName = $uploader->uploadFile($uploaded, $physicalPath);
                    $category->setImage($newFileName);
                }
                $entityManager->persist($category);
                $entityManager->flush();
                $categories = $repo->listCategories($new, $id);
                $this->addFlash('success', $translator->trans('admin.managecategories.created'));
            }
        }
        $used = [];
        foreach($categories as $one) {
            if(count($one->getKnifes())) {
                array_push($used, true);
            }
            else {
                array_push($used, false);
            }
        }
        return $this->render('admin/categories.html.twig', [
            "locale" =>  $loc,
            "new" =>  $new,
            "categoryname" => $category->getName(),
            "categoryfullname" => $category->getFullname(),
            'rank' => $category->getRank(),
            "categorydescription" => $category->getDescription(),
            "relatedcategories" =>  $category->getRelatedcategories(),
            "id" => $id,
            "knifeconflict" => $knifeconflict,
            "categories" => $categories,
            "used" => $used,
            "form" => $form->createView()
            ]
        );       
    }        
    // --------------------------------------------------------------------------
    #[Route('/categories/delete/{id}', name: 'bootadmin.categories.delete')]
    public function DeleteCategory(Request $request,
                                int $id,
                                Uploader $uploader,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        $repo = $entityManager->getRepository(Category::class);
        $category = $repo->find($id);
        $knifes = $category->getKnifes();
        if($knifes->count() > 0){
            $conflicts = array();
            foreach($knifes as $violation) { array_push($conflicts, ['name' => $violation->getName(), 'id' => $violation->getId()]);}
            $notice = $category->getName() . ' : ' .
                    $translator->trans('admin.managecategories.isused');
            $notice = $notice.' : '.$conflicts[0]['name'];
            $this->addFlash('error', $notice);
            return $this->redirectToRoute('bootadmin.categories', array( 'new' => "true",
                                                                        'id' => $category->getId(),
                                                                        'knifeconflict' => $conflicts[0]['id']));
        }
        if($category->getImage() !== '') {
            $physicalPath = $this->getParameter('categoryimages_directory');
            $uploader->deleteFile($physicalPath.'/'.$category->getImage());
        }
        $repo->remove($category, true);
        $this->addFlash('success', $translator->trans('admin.managecategories.deleted'));        
        return $this->redirectToRoute('bootadmin.categories', array(  'new' => "true" ));    }
    // --------------------------------------------------------------------------
    #[Route('/categories/update/{id}', name: 'bootadmin.categories.update')]
    public function UpdateCategory(Request $request,
                                int $id,
                                Uploader $uploader,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        $repo = $entityManager->getRepository(Category::class);
        $category = $repo->find($id);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // Take care of image
            $physicalPath = $this->getParameter('categoryimages_directory');
            // 1st, if already set and a new one is requested 
            // delete the previous file
            if(($category->getImage() !== '') && $form->get('image')->getData()) {
                $uploader->deleteFile($physicalPath.'/'.$category->getImage());
            }
            $uploaded = $form->get('image')->getData();
            if($uploaded !== null) {
                $newFileName = $uploader->uploadFile($uploaded, $physicalPath);
                $category->setImage($newFileName);
            }
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('admin.managecategories.updated'));
            return $this->redirectToRoute('bootadmin.categories', array( 'new' => "true"));
        }        
        return $this->redirectToRoute('bootadmin.categories', array(  'new' => "false",
                                                                'id' => $category->getId()
                                                            ));
    }
    // --------------------------------------------------------------------------
    // A C C E S S O R I E S     S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/accessories/home/{new?true}/{id?0}/{knifeconflict?0}', name: 'bootadmin.accessories')]
    public function AdminAccessories(Request $request,
                                $new,
                                $id,
                                $knifeconflict,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {         
        $loc = $this->locale($request); // Set the proper language for translations
        /** 
         * @var Accessories $accessory 
         * @var AccessoryRepository $repo
         * 
        */
        $accessory = new Accessories();
        $repo = $entityManager->getRepository(Accessories::class);
        if($id != 0) { // Edit an existing accessory
            $accessory = $repo->findOneBy(['id' => $id]);
        }
        $accessories = $repo->listAccessories();
        $form = $this->createForm(AccessoriesType::class, $accessory);
        if($new === 'abort') {  // The user aborted the modification
            $new = "true";
        }
        else {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $entityManager->persist($accessory);
                $entityManager->flush();
                $accessories = $repo->listAccessories();
                $this->addFlash('success', $translator->trans('admin.manageaccessories.created'));
            }
        }
        return $this->render('admin/accessories.html.twig', [
            "locale" =>  $loc,
            "new" =>  $new,
            "id" => $id,
            "accessoryname" => $accessory->getName(),
            "knifeconflict" => $knifeconflict,
            "accessories" => $accessories,
            "form" => $form->createView()
            ]
        );       
    }
    // --------------------------------------------------------------------------
    #[Route('/accessories/delete/{id}', name: 'bootadmin.accessories.delete')]
    public function DeleteAccessory(Request $request,
                                int $id,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        $repo = $entityManager->getRepository(Accessories::class);
        $accessory = $repo->find($id);
        $knifes = $accessory->getKnifes();
        if($knifes->count() > 0){
            $conflicts = array();
            foreach($knifes as $violation) { array_push($conflicts, ['name' => $violation->getName(), 'id' => $violation->getId()]);}
            $notice = $accessory->getName().' : '.$translator->trans('admin.manageaccessories.isused');
            $notice = $notice.' : '.$conflicts[0]['name'];
            $this->addFlash('error', $notice);
            return $this->redirectToRoute('bootadmin.accessories', array( 'new' => "true",
                                                                        'id' => $accessory->getId(),
                                                                        'knifeconflict' => $conflicts[0]['id']));
        }
        $repo->remove($accessory, true);
        $this->addFlash('success', $translator->trans('admin.manageaccessories.deleted'));        
        return $this->redirectToRoute('bootadmin.accessories', array(  'new' => "true" ));    
    }
    // --------------------------------------------------------------------------
    #[Route('/accessories/update/{id}', name: 'bootadmin.accessories.update')]
    public function UpdateAccessory(Request $request,
                                int $id,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        $repo = $entityManager->getRepository(Accessories::class);
        $accessory = $repo->find($id);
        $form = $this->createForm(AccessoriesType::class, $accessory);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($accessory);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('admin.manageaccessories.updated'));
            return $this->redirectToRoute('bootadmin.accessories', array( 'new' => "true"));
        }        
        return $this->redirectToRoute('bootadmin.accessories', array(  'new' => "false",
                                                                'id' => $accessory->getId()
                                                            ));
    }
    // --------------------------------------------------------------------------
    // P R I V A T E     S E R V I C E S 
    // --------------------------------------------------------------------------
    private function locale(Request $request) {
        $session = $request->getSession();
        if($session->has('bootadmin.lang')) {
            $loc = $session->get('bootadmin.lang');
        }
        else {
            $loc = $this->localeSwitcher->getLocale();
            $session->set('bootadmin.lang', $loc);
        }
        $this->localeSwitcher->setLocale($loc);
        return $loc;
    }
}
