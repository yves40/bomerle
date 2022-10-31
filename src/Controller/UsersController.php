<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Security\LoginAuthenticator;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route('/users')]
class UsersController extends AbstractController
{
    #[Route('/index', name: 'users.index')]
    public function index(): Response
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    // #[Route('/login', name: 'users.login')]
    // public function login(): Response
    // {
    //     return $this->render('users/login.html.twig', [
    //         'controller_name' => 'UsersController',
    //     ]);
    // }

    #[Route('/register', name: 'users.register')]
    public function register(
        Users $user = null,
        // ManagerRegistry $doctrine,
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        // UserAuthenticatorInterface $userAuthenticator,
        // LoginAuthenticator $authenticator,
        EntityManagerInterface $entityManager
        ): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->remove('created');
        $form->remove('role');
        $form->remove('lastlogin');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            )
            ->setConfirmpassword($user->getPassword());
            $user->setCreated(new DateTime('now', new DateTimeZone('Europe/Paris')));
            $entityManager->persist($user);
            $entityManager->flush();
            
            return $this->render('users/login.html.twig', [
                
            ]);   
        }
        else{
            return $this->render('users/register.html.twig', [
                'form' => $form->createView()
            ]);    
        }     
    }
}
