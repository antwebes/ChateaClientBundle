<?php

namespace Ant\Bundle\ChateaClientBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
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

        //add manager class
        if (isset($config['api_persistence']['api_manager']) && !empty($config['api_persistence']['api_manager'])) {
            $container->setParameter('antwebes_chateaclient_bundle.api.persistence.api_manager.class', $config['api_persistence']['api_manager']);
        }

        //register internal manager

        $manager = new Definition(
            $container->getParameter('antwebes_chateaclient_bundle.api.persistence.api_manager.class'),
                array(new Reference('antwebes_chateaclient_bundle.api.persistence.api_connection'))
        );
        $manager->setPublic(false);

        //register public manage
        $container->setDefinition('antwebes_chateaclient_bundle.api.persistence.api_manager',$manager);
        $container->setDefinition('antwebes_chateaclient_manager',$manager);



    }
}
