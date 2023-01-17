<?php

namespace App\Controller;

use Exception;
use App\Entity\Metals;
use App\Form\MetalsType;
use App\Entity\Mechanism;
use App\Form\MechanismType;
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
    #[Route('/metals/home/{new?true}/{metalname?#}/{id?10000}', name: 'bootadmin.metals')]
    public function AdminMetals(Request $request,
                                $new,
                                $metalname,
                                $id,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations

        $metal = new Metals();
        $repo = $entityManager->getRepository(Metals::class);
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
            "metalname" => $metalname,
            "id" => $id,
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
            $notice = $translator->trans('admin.managemetals.isused');
            $this->addFlash('error', $notice);
            return $this->redirectToRoute('bootadmin.metals', array( 'new' => "true"));
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
        // var_dump($metal->getName(), $metal->getId());die;
        return $this->redirectToRoute('bootadmin.metals', 
                            array(  'new' => "false", 
                                    'metalname' => $metal->getName(),
                                    'id' => $metal->getId()
                                ));
    }
    // --------------------------------------------------------------------------
    // M E C H A N I S M S     S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/mechanisms/home/{new?true}/{mechanismname?#}/{id?10000}', name: 'bootadmin.mechanisms')]
    public function AdminMechanisms(Request $request,
                                $new,
                                $mechanismname,
                                $id,
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
            "mechanismname" => $mechanismname,
            "id" => $id,
            "mechanisms" => $mechanisms,
            "form" => $form->createView()
            ]
        );               
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
