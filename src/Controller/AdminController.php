<?php

namespace App\Controller;

use Exception;
use App\Entity\Metals;
use App\Form\MetalsType;
use App\Services\FileHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
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
        $loc = $this->locale($request);
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
    // M E T A L S 
    // --------------------------------------------------------------------------
    #[Route('/metals/{new?true}', name: 'bootadmin.metals')]
    public function AdminMetals(Request $request,
                                $new,
                                EntityManagerInterface $entityManager
                            ): Response
    {
        $loc = $this->locale($request);

        $metal = new Metals();
        $repo = $entityManager->getRepository(Metals::class);
        $metals = $repo->listMetals();
        $form = $this->createForm(MetalsType::class, $metal);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($metal);
            $entityManager->flush();
            $metals = $repo->listMetals();
        }
        return $this->render('admin/metals.html.twig', [
            "locale" =>  $loc,
            "new" =>  $new,
            "metals" => $metals,
            "form" => $form->createView()
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/metals/delete/{id}', name: 'bootadmin.metals.delete')]
    public function DeleteMetal(Request $request,
                                int $id,
                                EntityManagerInterface $entityManager
                            ): Response
    {
        // $loc = $this->locale($request);
        // Search for the selected metal to be deleted
        $repo = $entityManager->getRepository(Metals::class);
        $metal = $repo->find($id);
        $knifes = $metal->getKnifes();
        // Is this metal related to any knife ?
        if($knifes->count() > 0){
            $this->addFlash('error', 'Ce métal est utilisé pour au moins un couteau');
            return $this->redirectToRoute('bootadmin.metals', array( 'new' => true));        }

        $this->addFlash('success', 'Ce métal peut être effacé');
        return $this->redirectToRoute('bootadmin.metals', array( 'new' => true));
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
