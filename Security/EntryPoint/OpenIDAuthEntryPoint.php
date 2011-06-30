<?php

/**
 * @author Kain Haart <dev@mail.kain-haart.info>
 */

namespace KainHaart\OpenIDAuthBundle\Security\EntryPoint;

/*
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
*/

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class OpenIDAuthEntryPoint implements AuthenticationEntryPointInterface
{

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
	$response = new RedirectResponse("/public");
	return $response;
	}
	
}