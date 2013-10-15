<?php

namespace Ant\Bundle\ChateaClientBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Ant\ChateaClientBundle\DependencyInjection\Factory\SecurityFactory;

class ChateaClientBundle extends Bundle
{
	public function build(ContainerBuilder $container)
    {
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new SecurityFactory());
    }
}
