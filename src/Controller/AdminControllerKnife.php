<?php

namespace App\Controller;

use Exception;

use App\Entity\Knifes;

use App\Form\KnifesType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/bootadmin')]
class AdminControllerKnife extends AbstractController
{
    private LocaleSwitcher $localeSwitcher;
    // --------------------------------------------------------------------------
    public function __construct(LocaleSwitcher $localeSwitcher)
    {
        $this->localeSwitcher = $localeSwitcher;
    }
    // --------------------------------------------------------------------------
    //      K N I F E S   S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/knives/all', name: 'bootadmin.knives.all')]
    public function home(Request $request,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        $loc = $this->locale($request);
        $knife = new Knifes();
        $repo = $entityManager->getRepository(Knifes::class);
        $allknives = $repo->findAll();

        $form = $this->createForm(KnifesType::class, $knife);
        return $this->render('admin/knives.html.twig', [
            "locale" =>  $loc,
            "new" => true,
            "allknives" => $allknives,
            "knifename" => '',
            "form" => $form->createView()
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/knives/delete/{id}', name: 'bootadmin.knives.delete')]
    public function DeleteMetal(Request $request,
                                int $id,
                                EntityManagerInterface $entityManager,
                                TranslatorInterface $translator
                            ): Response
    {
        $loc = $this->locale($request); // Set the proper language for translations
        $repo = $entityManager->getRepository(Knifes::class);
        $knife = $repo->find($id);
        $repo->remove($knife, true);
        $this->addFlash('success', $translator->trans('admin.manageknives.deleted'));
        return $this->redirectToRoute('bootadmin.knives.all', array( 'new' => "true"));
    }
    // --------------------------------------------------------------------------
    #[Route('/knives/edit/{id?0}', name: 'bootadmin.knives.edit')]
    public function update(Request $request,
                        int $id,    // If 0 : insert mode
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        $loc = $this->locale($request);
        $repo = $entityManager->getRepository(Knifes::class);
        $knife = new Knifes();
        if($id !== 0 ) $knife = $repo->find($id); // Update or new ? 
        $form = $this->createForm(KnifesType::class, $knife);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){   // Form submitted ? 
            $entityManager->persist($knife);
            $entityManager->flush();
            if($id === 0)  {    // Insert ? 
                $this->addFlash('success', $translator->trans('admin.manageknives.created'));
            }
            else {
                $this->addFlash('success', $translator->trans('admin.manageknives.updated'));
            }
            return $this->redirectToRoute('bootadmin.knives.all', array( 'new' => "true"));
        }
        return $this->render('admin/knife.html.twig', [
            "locale" =>  $loc,
            "id" => $id,
            "knife" => $knife,
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
