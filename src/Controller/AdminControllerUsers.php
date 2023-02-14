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
    //      E V E N T S    S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/users/all/{new?true}', name: 'bootadmin.users.all')]
    public function home(Request $request,
                        bool $new,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        $loc = $this->locale($request);
        $repo = $entityManager->getRepository(Users::class);
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        return $this->render('admin/users.html.twig', [
                "form" => $form->createView(),
                "locale" =>  $loc,
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
