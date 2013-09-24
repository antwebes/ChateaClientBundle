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
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new ChateaFactory());
    }

}
