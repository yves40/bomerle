<?php

namespace App\Controller;

use Error;
use Exception;

use App\Entity\Users;

use App\Form\UsersType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/bootadmin')]
class AdminControllerUsers extends AbstractController
{
    private LocaleSwitcher $localeSwitcher;
    // --------------------------------------------------------------------------
    public function __construct(LocaleSwitcher $localeSwitcher)
    {
        $this->localeSwitcher = $localeSwitcher;
    }
    // --------------------------------------------------------------------------
    //      U S E R S    S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/users/all/{new?true}/{id?0}', name: 'bootadmin.users.all')]
    public function home(Request $request,
                        $new,
                        $id,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        $repo = $entityManager->getRepository(Users::class);
        $users = $repo->findAll();
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        var_dump($new);
        switch($new) {
            case "true":
                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $user = new Users();
                    $users = $repo->findAll();
                    $this->addFlash('succes', $translator->trans('admin.manageusers.created'));
                }
                break;
            case "abort": 
                $new = true;
                $this->addFlash('succes', $translator->trans('admin.manageusers.cancel'));
                break;
            case "false":
                $user = $repo->findOneBy([ 'id' => $id]);
                break;
        }
        return $this->render('admin/users.html.twig', [
                "form" => $form->createView(),
                "locale" =>  $loc,
                "users" => $users,
                "new" => $new,
                "user" => $user
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/users/update/{id}', name: 'bootadmin.users.update')]
    public function updateEvent(Request $request,
                        int $id,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        $repo = $entityManager->getRepository(Users::class);
        $user = $repo->findOneBy(['id' => $id]);
        var_dump($user);
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && ( $form->isValid())){
            $entityManager->persist($user);
            $entityManager->flush();
            $users = $repo->findAll();
            $user = new Users();
            $this->addFlash('success', $translator->trans('admin.users.updated'));
            return $this->redirectToRoute('bootadmin.users.all', array( 'new' => "true"));
        }
        return $this->redirectToRoute('bootadmin.users.all', array( 'new' => "false",
                                                                        'id' => $id));
    }
    // --------------------------------------------------------------------------
    #[Route('/users/delete/{id}', name: 'bootadmin.users.delete')]
    public function deleteEvent(Request $request,
                        int $id,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        $repo = $entityManager->getRepository(Users::class);
        $event = $repo->findOneBy(['id' => $id]);
        try {
            $entityManager->remove($event);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('admin.users.deleted'));
        }
        catch(Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('bootadmin.users.all', array( 'new' => "true"));
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
