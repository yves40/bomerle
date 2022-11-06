<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Core\Token;
use App\Entity\Users;
use App\Form\UsersType;
use App\Services\MailO2;
use App\Entity\RequestsTracker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/users')]
class UsersController extends AbstractController
{
    // ------------------------------------------------------------------------
    #[Route('/index', name: 'users.index')]
    public function index(): Response
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }
    // ------------------------------------------------------------------------
    #[Route('/register', name: 'users.register')]
    public function register(
        Users $user = null,
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        MailO2 $mailo2
        ): Response
    {
        /* @$tks Token */
        $tks = null;
        /* @$rqt RequestTracker */
        $rqt = null;

        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->remove('created');
        $form->remove('role');
        $form->remove('lastlogin');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // ---------------------------------------------------------------------
            // Create the user in the DB
            // ---------------------------------------------------------------------
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            )
            ->setConfirmpassword($user->getPassword());
            $user->setCreated(new DateTime('now', new DateTimeZone('Europe/Paris')));
            $entityManager->persist($user);
            // ---------------------------------------------------------------------
            // Send the registration mail
            // ---------------------------------------------------------------------
            $tks = $mailo2->sendRegisterConfirmation($user->getEmail());
            // ---------------------------------------------------------------------
            // Track the registration request in the requests_tracker table
            // ---------------------------------------------------------------------
            $rqtracker = new RequestsTracker();
            date_default_timezone_set('Europe/Paris');
            $expires = date("U") + 1800; // 30 minutes delay before expiration
            $rqtracker = new RequestsTracker();
            $rqtracker->setRequestactiontype('Register');
            $rqtracker->setEmail($user->getEmail());
            $rqtracker->setCreated(new DateTime('now'));
            $rqtracker->setProcessed(new DateTime('now'));
            $rqtracker->setExpires($expires);
            $rqtracker->setToken($tks->getToken());
            $rqtracker->setSelector($tks->getSelector());
            $rqtracker->setStatus(RequestsTracker::STATUS_REQUESTED);
            $entityManager->persist($rqtracker);
            // ---------------------------------------------------------------------
            // Final commit of the global transaction
            // ---------------------------------------------------------------------
            $entityManager->flush();
            return $this->render('security/login.html.twig', ['last_username' => '', 'error' => array()]);   
        }
        else{
            // To remove later
            $user = $this->dummyUser();
            $form = $this->createForm(UsersType::class, $user);
            $form->remove('created');
            $form->remove('role');
            // ---------------------
            $form->remove('lastlogin');
                return $this->render('users/register.html.twig', [
                'form' => $form->createView()
            ]);    
        }     
    }
    // Dummy function used during tests
    private function dummyUser(): Users {
        $user = new Users();
        $user->setFirstname('Yves');
        $user->setLastname('Toubhans');
        $user->setEmail('yves.toubhans@free.fr');
        $user->setAddress('21 rue des moines épicuriens, 77200 Pontolt');
        $user->setPassword('1111');
        $user->setConfirmpassword('1111');
        return $user;
    }
}
