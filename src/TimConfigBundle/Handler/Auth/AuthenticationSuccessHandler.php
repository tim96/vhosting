<?php

namespace TimConfigBundle\Handler\Auth;

use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return parent::onAuthenticationSuccess($request, $token);
    }
}