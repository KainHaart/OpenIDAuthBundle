<?php

/**
 * @author Kain Haart <dev@mail.kain-haart.info>
 */

namespace KainHaart\OpenIDAuthBundle\Security\Firewall;

use KainHaart\OpenIDAuthBundle\Security\Authentication\Token\OpenIDToken;

use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OpenIDAuthListener extends AbstractAuthenticationListener
{
	protected function attemptAuthentication(Request $request)
    {		
		$openid = $request->request->get("openid");	
		$token = new OpenIDToken($openid,array("ROLE_USER"));
		try
			{
			$result = $this->authenticationManager->authenticate($token);		
			}
		catch(\Exception $ex)
			{
			die($ex->getMessage());
			}
		if($result instanceof OpenIDToken && $result->sAuthenticateURL)
			{
			# We should redirect user to provider's authentication page
			$response = new RedirectResponse($result->sAuthenticateURL);
			return $response;
			}
		return $result;
    }	
}
