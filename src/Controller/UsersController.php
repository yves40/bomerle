<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\Users;
use App\Form\UsersType;
use Symfony\Component\Mime\Email;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
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
            // ------------------------------------------------------------------------
            // Test email
            // Remember a worker has to consume the emails otherwise they won't be sent
            // Can be launched with this command :  
            //                  php bin/console messenger:consume async -vv
            // or
            //                  php bin/console messenger:consume async
            // This should be automatically started in production with a cron job...
            // The consumer must be stopped properly with this command:
            //                  php bin/console messenger:stop
            // ------------------------------------------------------------------------
            // $email = (new Email())
            //     ->from('noreply@beaumerledev.big-ben.fr')
            //     ->to('yves.toubhans@free.fr')
            //     //->cc('cc@example.com')
            //     //->bcc('bcc@example.com')
            //     ->replyTo('noreply@beaumerledev.big-ben.fr')
            //     //->priority(Email::PRIORITY_HIGH)
            //     ->subject('Time for Symfony Mailer!')
            //     ->html('<p>Sending emails is fun again!</p>'.
            //             '<p>If you started a consumer as explained here </p>'.
            //             '<p>https://symfony.com/doc/current/messenger.html#messenger-worker</p>'
            //     );
                
            // try {
            //     $mailer->send($email);
            // } catch (TransportExceptionInterface $e) {
            //     dd($e);         // Will manage this later
            // }            
            // ------------------------------------------------------------------------

            return $this->render('security/login.html.twig', ['last_username' => '', 'error' => array()]);   

        }
        else{
            return $this->render('users/register.html.twig', [
                'form' => $form->createView()
            ]);    
        }     
    }
}
