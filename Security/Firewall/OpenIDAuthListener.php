<?php

/**
 * @author Kain Haart <dev@mail.kain-haart.info>
 */

namespace KainHaart\OpenIDAuthBundle\Security\Firewall;

use KainHaart\OpenIDAuthBundle\Security\Authentication\Token\OpenIDToken;

use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class OpenIDAuthListener extends AbstractAuthenticationListener
{
	
	protected function isAuthProviderResponse(Request $request)
		{
		$aProviderResponse = $this->getAuthProviderResponse($request);
		$bResponsePresent = count($aProviderResponse) > 0;
		return $bResponsePresent;
		}
		
	protected function getAuthProviderResponse(Request $request)
		{
		$aProviderResponse = $request->query->all();	
		return $aProviderResponse;
		}
		
	protected function getIdentifier(Request $request)
		{
		$openid = $request->request->get("openid");	// TODO: Customize fieldname via options
		return $openid;
		}
	
	protected function attemptAuthentication(Request $request)
    {		
		# Specified OpenID identifier
		$openid = $this->getIdentifier($request);

		# Token	
		$token = new OpenIDToken($openid,array("ROLE_USER"));
		
		# Processing provider's redirect
		if($this->isAuthProviderResponse($request))
			{
			$token->setProviderResponse($this->getAuthProviderResponse($request));
			}

		# Authentication
		$result = $this->authenticationManager->authenticate($token);

		# Redirect user to provider's authentication page
		if($result instanceof OpenIDToken && !$result->isAuthenticated() && $result->sAuthenticateURL)
			{
			$response = new RedirectResponse($result->sAuthenticateURL);
			return $response;
			}

		# Result	
		return $result;
    }	
}
