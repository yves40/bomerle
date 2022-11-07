<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/robot')]
class RobotController extends AbstractController
{      
    #[Route('/registeruserconfirmed/{selector}/{token}', 
            name: 'user.register')]
    public function registerUser(string $selector, 
                                string $token, 
                                EntityManagerInterface $entityManager,
                                ): Response
    {
        return new Response("<p>Selector is $selector</p>".
                            "<p>Token is : $token</p>");
        return $this->render('security/login.html.twig',
             ['last_username' => '', 'error' => array()]);
    }
}
