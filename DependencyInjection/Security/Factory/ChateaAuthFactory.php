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
		$providerId = 'antwebes_chateaclient_bundle.security.provider.'.$id;
		$container
			->setDefinition(
					$providerId, 
					new DefinitionDecorator('antwebes_chateaclient_bundle.security.authentication.provider')
					)
				->replaceArgument(0, new Reference($userProvider));

		$listenerId = 'antwebes_chateaclient_bundle.security.listener.'.$id;
		
		$listener = $container->setDefinition($listenerId, new DefinitionDecorator('antwebes_chateaclient_bundle.security.authentication.listener'));

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
