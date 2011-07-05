<?php

/**
 * @author Kain Haart <dev@mail.kain-haart.info>
 */

namespace KainHaart\OpenIDAuthBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class OpenIDToken extends AbstractToken
{
    public function __construct($id = '', array $roles = array())
    {
        parent::__construct($roles);
		if($id)
			{
			$this->setUser($id);
			}
    }
	
	public $sAuthenticateURL = "";
	
	protected $aProviderResponce = array();
	
	public function setProviderResponse($data)
		{
		$this->aProviderResponce = $data;
		return $this;
		}
		
	public function getProviderResponse()
		{
		return $this->aProviderResponce;
		}
		
	public function hasProviderResponse()
		{
		return (boolean)($this->aProviderResponce);
		}

    public function getCredentials()
    {
        return '';
    }
}
