<?php
namespace Ant\Bundle\ChateaClientBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class ChateaAuthFactory implements SecurityFactoryInterface
{
	public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
	{
		$providerId = 'security.authentication.provider.chateaAuth.'.$id;
		$container
			->setDefinition(
					$providerId, 
					new DefinitionDecorator('antwebes.security.authentication.provider')
					)
				->replaceArgument(0, new Reference($userProvider));

		$listenerId = 'security.authentication.listener.chateaAuth.'.$id;
		
		$listener = $container->setDefinition($listenerId, new DefinitionDecorator('antwebes_chatea.auth.action_listener'));

		return array($providerId, $listenerId, $defaultEntryPoint);
	}

	public function getPosition()
	{
		return 'pre_auth';
	}

	public function getKey()
	{
		return 'chateaAuth';
	}

	public function addConfiguration(NodeDefinition $node)
	{
		
	}
}
