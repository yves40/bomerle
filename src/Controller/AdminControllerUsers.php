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
    #[Route('/users/edit/{id?0}', name: 'bootadmin.users.edit')]
    public function home(Request $request,
                        int $id,
                        UserPasswordHasherInterface $userPasswordHasher,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        $repo = $entityManager->getRepository(Users::class);
        $user = new Users();
        if($id !== 0 ) {        // Insert or update ?
               $user = $repo->find($id);
               $form = $this->createForm(UsersType::class, $user,
               ['validation_groups' => ['standard']]);
               $form->remove('password');
               $form->remove('confirmpassword');
        }
        else {
            $form = $this->createForm(UsersType::class, $user,
                ['validation_groups' => ['standard', 'passwordreset']]);
        }
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) { // Form submitted ? 
            if($id === 0) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $user->setConfirmpassword($user->getPassword());
                $user->setCreated(new DateTime('now', new DateTimeZone('Europe/Paris')));
                $user->setConfirmed(new DateTime('now', new DateTimeZone('Europe/Paris')));
            }
            $formroles = $form->get('role')->getData();
            foreach($formroles as $one) {
                $user->addRole($one);
            }
            $entityManager->persist($user);
            $entityManager->flush();
            $user = new Users();
            if($id === 0) {
                $this->addFlash('success', $translator->trans('admin.manageusers.created'));
            }
            else {
                $this->addFlash('success', $translator->trans('admin.manageusers.updated'));
            }
            return $this->redirectToRoute('bootadmin.users.list');
        }
        return $this->render('admin/usersedit.html.twig', [
            "form" => $form->createView(),
            "locale" =>  $loc,
            "user" => $user,
            "id" => $id
            ]
        );               
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
        $user = $repo->findOneBy(['id' => $id]);
        try {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('admin.manageusers.deleted'));
        }
        catch(Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }
        return $this->redirectToRoute('bootadmin.users.list');
    }
    // --------------------------------------------------------------------------
    #[Route('/users/resetpwd/{id}', name: 'bootadmin.users.resetpwd')]
    public function ResetUserPassword(Request $request,
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
            ['validation_groups' => ['passwordreset']]);
        $form->remove('email')->remove('firstname')->remove('lastname');
        $form->remove('address')->remove('role');
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) { // Form submitted ? 
            try {
                $user->setPassword(
                    $userPasswordHasher->hashPassword($user,
                        $form->get('password')->getData()
                    )
                );
                $user->setConfirmpassword($user->getPassword());
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', $translator->trans('admin.manageusers.passwordreset'));
                return $this->redirectToRoute('bootadmin.users.list');
            }
            catch(Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }
        return $this->render('admin/userspwdreset.html.twig', [
            "form" => $form->createView(),
            "locale" =>  $loc,
            "user" => $user,
            "id" => $id
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
