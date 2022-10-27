<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/login', name: 'users.login')]
    public function login(): Response
    {
        return $this->render('users/login.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    #[Route('/register', name: 'users.register')]
    public function register(
        Users $user = null,
        ManagerRegistry $doctrine,
        Request $request
        ): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->remove('created');
        $form->remove('role');
        $form->remove('lastlogin');
        // $form->add('confirm-password');
        $form->handleRequest($request);
        if($form->isSubmitted()){
            dd($form->getData());
        }
        
        if($request->getMethod()=='POST') //penser à vérifier que les données sont valides
        {
            $user=$form->getData();
            return $this->render('users/login.html.twig', [
                
            ]);   
        }else{
            return $this->render('users/register.html.twig', [
                'form' => $form->createView()
            ]);    
        }
    }
}
