<?php
namespace Ant\Bundle\ChateaClientBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ant\Bundle\ChateaClientBundle\DependencyInjection\Security\Factory\ChateaFactory;
 
class ChateaClientBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);
		$custon_id = "antwebs_secured_area";
		$config = array();
		$defaultEntryPoint = null;
		$extension = $container->getExtension('security');
		$extension->addSecurityListenerFactory(new ChateaFactory($container,$custon_id,$config,$defaultEntryPoint));
	}
}
