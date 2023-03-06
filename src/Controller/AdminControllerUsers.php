<?php

namespace App\Controller;

use Error;
use DateTime;

use Exception;
use DateTimeZone;

use App\Entity\Users;
use App\Form\UsersType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
    #[Route('/users/edit/{new?true}/{id?0}', name: 'bootadmin.users.edit')]
    public function home(Request $request,
                        $new,
                        $id,
                        UserPasswordHasherInterface $userPasswordHasher,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        $repo = $entityManager->getRepository(Users::class);
        $users = $repo->findBy([], ['email' => 'ASC']);
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user,
                         ['validation_groups' => ['standard', 'passwordreset']]);
        switch($new) {
            case "true":
                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid()) {
                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('password')->getData()
                        )
                    );
                    $user->setConfirmpassword($user->getPassword());
                    $user->setCreated(new DateTime('now', new DateTimeZone('Europe/Paris')));
                    $user->setConfirmed(new DateTime('now', new DateTimeZone('Europe/Paris')));
                    $formroles = $form->get('role')->getData();
                    foreach($formroles as $one) {
                        $user->addRole($one);
                    }
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $user = new Users();
                    $users = $repo->findBy([], ['email' => 'ASC']);
                    $this->addFlash('success', $translator->trans('admin.manageusers.created'));
                }
                break;
            case "abort": 
                $new = true;
                $this->addFlash('success', $translator->trans('admin.manageusers.cancel'));
                break;
            case "false":
                $user = $repo->findOneBy([ 'id' => $id]);
                $form = $this->createForm(UsersType::class, $user,
                    ['validation_groups' => ['standard', 'passwordreset']]);
                break;
        }
        return $this->render('admin/usersadd.html.twig', [
                "form" => $form->createView(),
                "locale" =>  $loc,
                "new" => $new,
                "user" => $user
            ]
        );               
    }
    // --------------------------------------------------------------------------
    #[Route('/users/update/{id}', name: 'bootadmin.users.update')]
    public function updateUser(Request $request,
                        int $id,
                        UserPasswordHasherInterface $userPasswordHasher,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        $repo = $entityManager->getRepository(Users::class);
        $user = $repo->findOneBy(['id' => $id]);
        $form = $this->createForm(UsersType::class, $user,
                ['validation_groups' => ['standard', 'passwordreset']]);
        $form->handleRequest($request);
        // POST or GET ? 
        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setConfirmpassword($user->getPassword());
            $user->setCreated(new DateTime('now', new DateTimeZone('Europe/Paris')));
            $user->setConfirmed(new DateTime('now', new DateTimeZone('Europe/Paris')));
            $formroles = $form->get('role')->getData();
            foreach($formroles as $one) {
                $user->addRole($one);
            }
            $entityManager->persist($user);
            $entityManager->flush();
            $users = $repo->findAll();
            $user = new Users();
            $this->addFlash('success', $translator->trans('admin.manageusers.updated'));
            return $this->redirectToRoute('bootadmin.users.edit', array( 'new' => "true"));

        }
        $users = $repo->findBy([], ['email' => 'ASC']);
        return $this->render('admin/usersadd.html.twig', [
            "form" => $form->createView(),
            "locale" =>  $loc,
            "users" => $users,
            "new" => "false",
            "user" => $user
        ]);               
    }
    // --------------------------------------------------------------------------
    #[Route('/users/list', name: 'bootadmin.users.list')]
    public function listUsers(Request $request,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        $repo = $entityManager->getRepository(Users::class);
        $users = $repo->findBy([], ['email' => 'ASC']);
        return $this->render('admin/users.html.twig', [
            "locale" =>  $loc,
            "users" => $users,
        ]);               
    }
    // --------------------------------------------------------------------------
    #[Route('/users/delete/{id}', name: 'bootadmin.users.delete')]
    public function deleteUser(Request $request,
                        int $id,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        $repo = $entityManager->getRepository(Users::class);
        $event = $repo->findOneBy(['id' => $id]);
        try {
            $entityManager->remove($event);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('admin.manageusers.deleted'));
        }
        catch(Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('bootadmin.users.list', array( 'new' => "true"));
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
