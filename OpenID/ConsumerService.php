<?php
/**
 * OpenID service 
 * @author Ziumin
 * @author Kain Haart <dev@mail.kain-haart.info>
 */
namespace KainHaart\OpenIDAuthBundle\OpenID;

use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * OpenID service 
 * @author Ziumin
 * @uses LightOpenID
 */
class ConsumerService 
{
	
	private $options;
	private $container;
	private $profile = 0;
	
	/**
	 * Class constructor. Just saves variables. 
	 * @param $options
	 * @param $container
	 */
	public 	function __construct($options, ContainerInterface $container = null)
	{
		$this->options = $options;
		$this->setProfile($options['profile']);
		$this->container = $container;
	}
	
	/**
	 * Generates redirect response to redirect user 
	 * to the OpenID provider authentification page
	 * @param string $identity User identity string. 
	 * 						   Uses "default" config option when identity is null.
	 * @uses LightOpenID::returnUrl
	 * @uses LightOpenID::required
	 * @uses LightOpenID::optional
	 * @uses LightOpenID::identity
	 * @uses LightOpenID::authUrl()
	 * @uses $options
	 * @uses $container
	 */
	public function start($identity = 0)
	{
		$openid = new LightOpenID;
		if (@$this->profile['return'])
		{
			$openid->returnUrl = $this->container->get('router')->generate($this->profile['return'], array(), true);	
		}
		$openid->required = isset($this->profile['required'])?$this->profile['required']:array();
		$openid->optional = isset($this->profile['optional'])?$this->profile['optional']:array();
		$openid->identity = $identity ? $identity:$this->profile['default'];
		$sAuthURL = $openid->authUrl();
		return $sAuthURL;
	}
	
	/**
	 * Gets and checks OpenID results returned by OpenID provider
	 * @uses LightOpenID::validate()
	 * @uses LightOpenID::getAttributes()
	 * @uses LightOpenID::identity
	 * @return mixed Returned parameters or 0 on error/fail
	 */
	public function finish()
	{
		$openid = new LightOpenID;
			if($openid->mode == 'cancel' || !$openid->mode) 
			{
				return 0;
			} else {
				if (!$openid->validate()) return 0;
				$attr = $openid->getAttributes();
				$attr['id'] = $openid->identity;
				return $attr;
			}
	}
	
	/**
	 * Sets current profile
	 * @param string $profile Name of profile
	 */
	public function setProfile($profile)
	{
		if (isset($this->options[$profile . '_profile']))
		{
			$this->profile = $this->options[$profile . '_profile'];
		}
	}
}