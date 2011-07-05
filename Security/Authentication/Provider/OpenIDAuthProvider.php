<?php

/**
 * @author Kain Haart <dev@mail.kain-haart.info>
 */

namespace KainHaart\OpenIDAuthBundle\Security\Authentication\Provider;

use KainHaart\OpenIDAuthBundle\Security\Authentication\Token\OpenIDToken;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class OpenIDAuthProvider implements AuthenticationProviderInterface
{
	
	protected $oid;

	public function __construct($serviceOpenID)
		{
		$this->oid = $serviceOpenID;
		}
		
	public function authenticate(TokenInterface $token)
		{	
		if($token->hasProviderResponse())
			{
			return $this->finish($token);
			}
		else
			{
			return $this->start($token);
			}
		}
		
	public function start(TokenInterface $token)
		{
		# Provider tries to authenticate token
		$oid = $this->oid;
		# Requesting authentication from openid provider (start)
		$identify = $token->getUser();
		$sAuthURL = $oid->start($identify);
		if (!$sAuthURL)
			{
			# Cannot start OpenID
			throw new \Exception("Cannot start OpenID");
			}
		$token->sAuthenticateURL = $sAuthURL;
		# we've figured out that user should be redirected to {$sAuthURL}
		return $token;
		}
	
    public function finish(TokenInterface $token)
    {
		$oid = $this->oid;	
		# Process OpenID provider response (finish)
		$attributes = $oid->finish();
		if (!$attributes) 
			{
			throw new AuthenticationException('Test authentication failed.');	
			# User canceled request or another error happened (validation failed, etc...)
			}
		$openid = $attributes['id'];
		$token = new OpenIDToken($openid,array("ROLE_USER"));
		$token->setAuthenticated(true);
		return $token;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof OpenIDToken;
    }
}