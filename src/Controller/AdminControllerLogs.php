<?php

namespace App\Controller;

use DateTime;

use App\Entity\Dblog;

use App\Services\DatesHandler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/bootadmin')]
class AdminControllerLogs extends AbstractController
{

    private LocaleSwitcher $localeSwitcher;
    // --------------------------------------------------------------------------
    public function __construct(LocaleSwitcher $localeSwitcher)
    {
        $this->localeSwitcher = $localeSwitcher;
    }
    // --------------------------------------------------------------------------
    //      L O G S    S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/logs/all/', name: 'bootadmin.logs.all')]
    public function home(Request $request,
                        EntityManagerInterface $entityManager,
                        TranslatorInterface $translator): Response
    {
        date_default_timezone_set('Europe/Paris');
        $loc = $this->locale($request);
        $now = new DateTime();
        $repo = $entityManager->getRepository(Dblog::class);
        $logs = $repo->findByDateDesc();
        $dblogentity = new Dblog();
        $severitylabels = $dblogentity->getSeverityLabels();

        $filterdates = new DatesHandler();
        dump($filterdates);
        $form = $this->createFormBuilder($filterdates)
            ->add('startdate', DateType::class)
            ->add('enddate', DateType::class)
            ->getForm();

        return $this->render('admin/logs.html.twig', [
                "form" => $form->createView(),
                "locale" =>  $loc,
                "logs" => $logs,
                "severitylabels" =>$severitylabels
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
