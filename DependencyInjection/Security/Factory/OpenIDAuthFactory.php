<?php

/**
 * @author Kain Haart <dev@mail.kain-haart.info>
 */

namespace KainHaart\OpenIDAuthBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;

class OpenIDAuthFactory extends AbstractFactory
{

    public function getPosition()
    {
        return 'form';
    }

    public function getKey()
    {
        return 'openidauth';
    }

    protected function getListenerId()
    {
        return 'openidauth.security.authentication.listener';
    }
	
	protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
		return 'openidauth.security.authentication.provider';
	}

	protected function createEntryPoint($container, $id, $config, $defaultEntryPoint)
    {
        $entryPointId = 'security.entry_point.openidauth'.$id;
        $container
            ->setDefinition($entryPointId, new DefinitionDecorator('openidauth.security.entry_point'))
			->addArgument($config['login_path'])
	        ;

        return $entryPointId;
    }
 
}