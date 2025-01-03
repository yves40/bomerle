<?php

namespace App\Security;

use App\Services\DBlogger;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

// -----------------------------------------------------------------------------------------------------------
class BootLoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'bootadmin.login';
    private $dblogger;
    private $applicationmodule = 'BootLoginFormAuthenticator';
    
    // -----------------------------------------------------------------------------------------------------------
    public function __construct(private UrlGeneratorInterface $urlGenerator, private ManagerRegistry $mgr)
    {
        $this->dblogger = new DBlogger($this->mgr);
    }

    // -----------------------------------------------------------------------------------------------------------
    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }
    // -----------------------------------------------------------------------------------------------------------
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName) : ?Response
    {
        // if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
        //     return new RedirectResponse($targetPath);
        // }
        $useremail = $request->request->get('email', '');
        $this->dblogger->info($useremail,  
                            'Login success',
                            $this->applicationmodule, 
                            $useremail
                        );
        $request->getSession()->set('email',$request->request->get('email', ''));
        return new RedirectResponse($this->urlGenerator->generate('bootadmin.home'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }
    // -----------------------------------------------------------------------------------------------------------
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $this->dblogger->error($request->request->get('email', ''),
                            'Login failure',
                            $this->applicationmodule,
                            $request->request->get('email', '')
                        );
        $request->getSession()->getFlashBag()->add('error', 'Identifiant ou mot de passe incorrect');
        return new RedirectResponse($this->urlGenerator->generate(self::LOGIN_ROUTE));
    }
    // -----------------------------------------------------------------------------------------------------------
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
