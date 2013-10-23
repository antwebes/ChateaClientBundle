<?php

namespace Ant\Bundle\ChateaClientBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ChateaClientExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {


        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/services'));
        $loader->load('common.xml');
        $loader->load('security.xml');
        $loader->load('manager.xml');


        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter('chatea_client.limits.channel_manager', $config['limits']['channel_manager']);
        $container->setParameter('chatea_client.limits.user_manager', $config['limits']['user_manager']);
        $container->setParameter('chatea_client.app_auth.client_id', $config['app_auth']['client_id']);
        $container->setParameter('chatea_client.app_auth.secret', $config['app_auth']['secret']);
    }
}
