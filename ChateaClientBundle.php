<?php

namespace Ant\Bundle\ChateaClientBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ant\Bundle\ChateaClientBundle\DependencyInjection\Factory\SecurityFactory;

class ChateaClientBundle extends Bundle
{
	public function build(ContainerBuilder $container)
    {
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new SecurityFactory());
    }
}
