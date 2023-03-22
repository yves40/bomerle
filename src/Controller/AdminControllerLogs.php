<?php

namespace App\Controller;

use DateTime;

use App\Entity\Dblog;
use App\Form\DateRangeType;
use App\Services\DatesHandler;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
        $repo = $entityManager->getRepository(Dblog::class);
        // findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
        $logs = $repo->findBy([], [ 'logtime' => 'DESC'], $repo::RETRIEVEDMAX, 0);
        // $logs = $repo->findByDateDesc();
        $dblogentity = new Dblog();
        $severitylabels = $dblogentity->getSeverityLabels();
        $form = $this->createForm(DateRangeType::class);
        
        return $this->render('admin/logs.html.twig', [
                "form" => $form->createView(),
                "locale" =>  $loc,
                "logs" => $logs,
                "severitylabels" =>$severitylabels
            ]
        );               
    }
    // --------------------------------------------------------------------------
    //      J S O N   S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/logs/page/{pagenum?1}', name: 'bootadmin.logs.page')]
    public function page(Request $request,EntityManagerInterface $emgr, int $pagenum) {
        date_default_timezone_set('Europe/Paris');
        // $loc = $this->locale($request);
        // $loc = $this->localeSwitcher->getLocale();
        $loc = $request->getSession()->get('bootadmin.lang');
        try {
            $repo = $emgr->getRepository(Dblog::class);
            // findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
            $offset = ($pagenum - 1) * $repo::RETRIEVEDMAX;
            $logs = $repo->findBy([], [ 'logtime' => 'DESC'], $repo::RETRIEVEDMAX, $offset);
            return $this->json([
                'offset' => $offset,
                'pagenumber' => $pagenum,
                'locale' => $loc,
                'logs' => $logs
            ], 200);
        }
        catch(Exception $e) {
            return $this->json([
                'message' => 'bootadmin.logs.page ERROR',
                'errmess' => $e->getMessage(),
                'locale' => $loc
            ], 400);
        }
    }
    #[Route('/logs/getcount/', name: 'bootadmin.logs.getcount')]
    public function getLogsCount(Request $request,EntityManagerInterface $emgr) {
        try {
            $repo = $emgr->getRepository(Dblog::class);
            $nblogs = $repo->getLogEntriesCount();
            return $this->json([
                'message' => 'bootadmin.logs.getcount called',
                'nblogs' => $nblogs
            ], 200);    
        }
        catch(Exception $e) {
            return $this->json([
                'message' => 'bootadmin.logs.getcount ERROR',
                'errmess' => $e->getMessage()
            ], 400);
        }
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
