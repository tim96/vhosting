<?php

namespace TimConfigBundle\Handler\Auth;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationFailureHandler extends DefaultAuthenticationFailureHandler
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return parent::onAuthenticationFailure($request, $exception);
    }
}