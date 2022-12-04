<?php

namespace App\Security;

use App\Services\FileHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        $fh = new FileHandler();
        $content = $fh->getFileContent('redirect/accessdenied.html');
        return new Response($content, 403);
    }
}