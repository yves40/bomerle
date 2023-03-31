<?php

namespace App\Controller;

use DateTime;

use Exception;
use App\Entity\Dblog;
use DateTimeInterface;

use App\Form\DateRangeType;
use App\Services\DatesHandler;
use Doctrine\ORM\Query\Expr\From;
use Doctrine\ORM\Query\AST\FromClause;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Length;

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
    public function page(Request $request,
                    EntityManagerInterface $emgr, 
                    int $pagenum,
                    )
    {

        $data = file_get_contents("php://input");
        $payload = json_decode($data, true);
        $allargs = $payload['allargs'];

        $searchtext = $allargs[1]['searchtext'];    // Any text to search for ?
        $requestedlevels = $allargs[0]['levels'];   // Get levels
        $before = DateTime::createFromFormat(DateTimeInterface::ATOM,
                                $allargs[2]['date']['year'].'-'
                                .$allargs[2]['date']['month'].'-'
                                .$allargs[2]['date']['day'].'T23:59:59+00:00'
                            );
        $after = DateTime::createFromFormat(DateTimeInterface::ATOM, 
                                $allargs[3]['date']['year'].'-'
                                .$allargs[3]['date']['month'].'-'
                                .$allargs[3]['date']['day'].'T00:00:00+00:00');

        date_default_timezone_set('Europe/Paris');
        $loc = $request->getSession()->get('bootadmin.lang');
        try {
            $repo = $emgr->getRepository(Dblog::class);
            $offset = ($pagenum - 1) * $repo::RETRIEVEDMAX;
            // findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
            // $logs = $repo->findBy([], [ 'logtime' => 'DESC'], $repo::RETRIEVEDMAX, $offset);
            $qb = $emgr->createQueryBuilder();
            $qb->select('logs')
                ->from('App\Entity\Dblog', 'logs')
                ->andWhere('logs.logtime <= :before')
                ->andWhere('logs.logtime >= :after')
                ->setParameter('before', $before)
                ->setParameter('after', $after)
                ->orderBy('logs.logtime', 'DESC')
                ->setFirstResult($offset)
                ->setMaxResults($repo::RETRIEVEDMAX);
            // Any search text entered ? 
            $wild = '';
            if(strlen($searchtext) !== 0 ) {
                // Add wildcards
                $wild = '%'.$searchtext.'%';
                $qb->select('logs')
                    ->andWhere('logs.message like :searchtext or logs.action like :searchtext or logs.module like :searchtext')
                    ->setParameter('searchtext', $wild)
                    ;
            }
            /* 
                Check requested levels : The received array looks like 
                    levels: 
                        0:{id: 'debug', state: true}
                        1:{id: 'informational', state: true}
                        2:{id: 'warning', state: true}
                        3:{id: 'error', state: true}
                        4:{id: 'fatal', state: true}            

                        const DEBUG = 0;
                        const INFORMATIONAL = 1;
                        const WARNING = 2;
                        const ERROR = 3;
                        const FATAL = 4;
            */
            $levelscriteria = '';
            foreach($requestedlevels as $level) {
                $state = $level['state'];
                switch($level['id']) {
                    case 'debug':
                        if($state)$levelscriteria .= '0,';
                        break;
                    case 'informational':
                        if($state)$levelscriteria .= '1,';
                        break;
                    case 'warning':
                        if($state)$levelscriteria .= '2,';
                        break;
                    case 'error':
                        if($state)$levelscriteria .= '3,';
                        break;
                    case 'fatal':
                        if($state)$levelscriteria .= '4,';
                        break;
                }
            }
            $critlength = strlen($levelscriteria);
            if( $critlength !== 0 ) {
                $levels = substr($levelscriteria, 0, $critlength - 1);
                $qb->select('logs')
                ->andWhere('logs.severity in('.$levels.')') // possible SQL injection here but param not working
                ;
            }
            // Fire    
            $dql = $qb->getDql();
            $logs = $qb->getQuery()->getResult();
            
            return $this->json([
                'offset' => $offset,
                'pagenumber' => $pagenum,
                'locale' => $loc,
                'logs' => $logs,
                'allargs' => $allargs,
                'before' => $before,
                'after' => $after,
                'dql' => $dql,
                'wild' => $wild,
                'levels' => $levels
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
    // --------------------------------------------------------------------------
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
