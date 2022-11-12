<?php

namespace App\Controller;

use DateTime;
use App\Entity\Users;
use App\Form\UsersType;
use App\Entity\RequestsTracker;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RequestsTrackerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/robot')]
class RobotController extends AbstractController
{    
    // -------------------------------------------------------------------------------------------------------  
    #[Route('/registeruserconfirmed/{selector}/{token}', name: 'user.register')]
    public function registerUser(string $selector, 
                                string $token, 
                                EntityManagerInterface $entityManager,
                                ): Response
    {
        date_default_timezone_set('Europe/Paris');
        $status = RequestsTracker::STATUS_ERROR;
        /* @$rqtr RequestsTrackerRepository */
        $rqtr = $entityManager->getRepository(RequestsTracker::class);
        /* @$usrr UsersRepository */
        $usrr = $entityManager->getRepository(Users::class);
        $userrequest = $rqtr->findRequestBySelector($selector);
        // Any match ??
        if(!empty($userrequest)) {
            $userrequest = $rqtr->findRequestBySelector($selector)[0];
            if($userrequest->getToken() === $token) {
                // Now check the expiration date
                $currentdate = date("U");
                if($currentdate <= $userrequest->getExpires()) {
                    $status = RequestsTracker::STATUS_PROCESSED;
                    $userrequest->setStatus($status); 
                    $feedback = "Votre demande de création de compte est acceptée";
                    // Set confirmation date in the user table
                    $regusr = $usrr->findUserByEmail($userrequest->getEmail());
                    if(!empty($regusr)){    // Ok the user with this email is known, update it's confirmation date
                        $regusr = $regusr[0];
                        $regusr->setConfirmed(new DateTime('now'));
                        $entityManager->persist($regusr);
                    }
                    else {
                        $status = RequestsTracker::STATUS_ERROR;
                        $userrequest->setStatus($status);
                        $feedback = "Votre demande d'enregistrement avec cet email [ $userrequest->getEmail() ] est invalide ";
                    }
                }
                else {
                    $status = RequestsTracker::STATUS_EXPIRED;
                    $userrequest->setStatus($status);
                    $atlast = date('d-m-Y H:i',$userrequest->getExpires());
                    $feedback = "Votre demande d'enregistrement est rejetée car elle aurait dû être soumise avant le : $atlast";
                }
            }
            else {
                $status = RequestsTracker::STATUS_REJECTED;
                $userrequest->setStatus($status);
                $feedback = "Demande rejetée, le jeton de confirmation est altéré";
            }
            $userrequest->setProcessed(new DateTime('now'));
            $entityManager->persist($userrequest);  // Update tracking table
            $entityManager->flush();
        }
        else {
            $status = RequestsTracker::STATUS_ERROR;
            $feedback = "Votre demande est inconnue ou a déjà été confirmée";
        }
        // Some feedback for the user attempting to activate its account
        switch($status) {
            case RequestsTracker::STATUS_PROCESSED: 
                $this->addFlash('success', $feedback);
                break;
            case RequestsTracker::STATUS_EXPIRED: 
                $this->addFlash('info', $feedback);
                break;
            case RequestsTracker::STATUS_REJECTED:
                $this->addFlash('error', $feedback);
                break;
            case RequestsTracker::STATUS_ERROR:
                $this->addFlash('error', $feedback);
                break;
        }
        
        return $this->redirectToRoute('home');
    }
    // -------------------------------------------------------------------------------------------------------  
    #[Route('/resetmypassword/{selector}/{token}', name: 'user.passwordreset')]
    public function resetpassword(string $selector, 
                                string $token, 
                                EntityManagerInterface $entityManager,
                                ): Response
    {
        date_default_timezone_set('Europe/Paris');
        $status = RequestsTracker::STATUS_ERROR;
        /* @$rqtr RequestsTrackerRepository */
        $rqtr = $entityManager->getRepository(RequestsTracker::class);
        /* @$usrr UsersRepository */
        $usrr = $entityManager->getRepository(Users::class);
        $userrequest = $rqtr->findRequestBySelector($selector);
        // Any match ??
        if(!empty($userrequest)) {
            $userrequest = $rqtr->findRequestBySelector($selector)[0];
            if($userrequest->getToken() === $token) {
                // Now check the expiration date
                $currentdate = date("U");
                if($currentdate <= $userrequest->getExpires()) {
                    $feedback = "Votre demande de réinitialiation de mot de passe est acceptée";
                    $status = RequestsTracker::STATUS_PROCESSED;
                    $userrequest->setStatus($status); 
                }
                else {
                    $status = RequestsTracker::STATUS_EXPIRED;
                    $userrequest->setStatus($status);
                    $atlast = date('d-m-Y H:i',$userrequest->getExpires());
                    $feedback = "Votre demande de reset de mot de passe devait être effectué avant le : $atlast";
                }
            }
            else {
                $status = RequestsTracker::STATUS_REJECTED;
                $userrequest->setStatus($status);
                $feedback = "Demande rejetée, le jeton de confirmation est altéré";
            }
            $userrequest->setProcessed(new DateTime('now'));
            $entityManager->persist($userrequest);  // Update tracking table
            $entityManager->flush();
        }
        else {
            $status = RequestsTracker::STATUS_ERROR;
            $feedback = "Votre demande est inconnue ou a déjà été confirmée";
        }
        // What do we do now ???  Proceed to password reset or send a message
        if($status == RequestsTracker::STATUS_PROCESSED) {
            $this->addFlash('success', 'Saisissez un nouveau mot de passe');
            $user = $usrr->findUserByEmail($userrequest->getEmail());
            if(!empty($user)) {
                $user = $user[0];
                // Here we we test another method to create the form.
                // We do not use the usual $this->createForm(UsersType::class, $user);
                // This is just to set the action URL
                $form = $this->createResetForm($user);
                return $this->render('security/passwordreset.html.twig', [ 'form' => $form->createView(), 'usermail' => $user->getEmail()]);
            }
        }
        else {
            // Some feedback for the user attempting to re-activate its account
            switch($status) {
                case RequestsTracker::STATUS_EXPIRED: 
                    $this->addFlash('info', $feedback);
                    break;
                case RequestsTracker::STATUS_REJECTED:
                    $this->addFlash('error', $feedback);
                    break;
                case RequestsTracker::STATUS_ERROR:
                    $this->addFlash('error', $feedback);
                    break;
            }        
            return $this->redirectToRoute('home');
        }
    }
    // -------------------------------------------------------------------------------------------------------  
    #[Route('/setpassword', name: 'user.setpassword')]
    public function setUserPassword(EntityManagerInterface $entityManager,
                                    Request $request): Response
    {
        $user = new Users();
        $form = $this->createResetForm($user);
        $form->handleRequest($request);
        if($form->isValid()){
            $this->addFlash('success', 'Votre mot de passe a été réinitialisé');
            dd($user);
            return $this->redirectToRoute('home');
        }
        else {
            return $this->render('security/passwordreset.html.twig', [ 'form' => $form->createView(), 'usermail' => $user->getEmail()]);        
        }
    }

    private function createResetForm($user) {
        return $this->createFormBuilder($user)
        ->setAction($this->generateUrl('user.setpassword'))
        ->setMethod('POST')
        ->add('firstname')
        ->add('lastname')
        ->add('email', null, [ 'attr' => [ 'placeholder' => 'A valid email please']])
        ->add('address')
        ->add('password', PasswordType::class, [ 'attr' => [ 'placeholder' => 'Password 4 to 20 characters']])
        ->add('confirmpassword', PasswordType::class,  [ 'attr' => [ 'placeholder' => 'Password 4 to 20 characters']])
        ->getForm();
    }
}
