<?php

namespace App\Controller;

use Exception;

use App\Entity\Knifes;
use App\Entity\Contact;
use App\Services\MailO2;
use App\Form\ContactType;
use App\Services\DBlogger;
use App\Services\DataAccess;
use App\Repository\KnifesRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/public')]
class SiteController extends AbstractController
{
    private LocaleSwitcher $localeSwitcher;
    const MODULE = 'SiteController';
    const CONTACTMESSAGEMAXLENGTH = 200;

    public function __construct(LocaleSwitcher $localeSwitcher) {
        $this->localeSwitcher = $localeSwitcher;
    }
    // --------------------------------------------------------------------------
    #[Route('/main', name: 'public.main')]
    public function public(Request $request, 
                TranslatorInterface $translator): Response
    {
        $contact = new Contact();

        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);
        $loc = $this->locale($request); // Set the proper language for translations
        return $this->render('main.html.twig', [
            "locale" =>  $this->localeSwitcher->getLocale(),
            'formcontact' => $formContact->createView(),
            ]
        );               
    }
    // ------------------------------------------------------------------------
    // Public page lang switch handler
    // ------------------------------------------------------------------------
    #[Route('/switchlang/{locale}', name: 'public.switchlang')]
    public function switchlang(
        Request $request, string $locale
    ): Response
    {
        $request->getSession()->set('bootadmin.lang', $locale);
        $this->localeSwitcher->setLocale($locale);
        return $this->redirectToRoute('public.main');
    }
    // --------------------------------------------------------------------------
    // J S O N  S E R V I C E S 
    // --------------------------------------------------------------------------
    #[Route('/contactrequest', name: 'public.contactrequest')]
    /*
        Had to use a service in order to get a valid repository access
        This is why the 1st parameter is a DataAccess class
        It just return a repository
        Strangely enough, directly injecting an EntityManagerInterface never worked ;-(
    */
    public function postContactRequest(MailO2 $mailer, 
                                DataAccess $da, 
                                DBlogger $dblog )
    {
        try {
            /**  @var Knifes $knife */
            /**  @var KnifesRepository $repo */
            $data = file_get_contents("php://input");
            $payload = json_decode($data, true);
            $knifeid = $payload['knifeid'];
            $message = $payload['message'];
            $requestor = $payload['email'];
            $knifename = 'No Knife';
            $subject = 'Contact Request';
            if($knifeid != 0) {
                $repo = $da->getRepository(Knifes::class);
                $knife = $repo->find($knifeid);
                $knifename = $knife->getName();
                $subject = 'Contact Request for knife '.$knifename;
            }
            // Debug trace
            $dblog->info(substr($message, 0, self::CONTACTMESSAGEMAXLENGTH).'...', 
                        $subject, 
                        self::MODULE, 
                        $requestor);
            /* 
                Send the email
                During Dev op MAIL_ADMIN is set to yves77340@gmail.com
                in .env.local
                On a Windows platform $_ENV['MAIL_FROM'] sometimes does not work
                so in this case we get the admin email from properties.js.
                The value is transmitted in the Ajax request.
            */
            $to = $_ENV['MAIL_ADMIN'];
            if($to == null) {
                $to = $payload['adminmail'];
            }
            $mailer->sendEmail($requestor, 
                                $to, 
                                $subject,
                                $message);
            return $this->json([
                'message' => 'public.contactrequest OK',
                'knifename' => $knifename,
                'knifeid' => $knifeid,
                'postedmessage' => $message,
                'to' => $to
            ], 200);        
        }
        catch(Exception $e) {
            return $this->json([
                'message' => 'public.contactrequest KO ',
                'error' => $e
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
