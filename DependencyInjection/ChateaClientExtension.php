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
        $container->setParameter('chatea_client.limits.channel_type_manager', $config['limits']['channel_type_manager']);
        $container->setParameter('chatea_client.limits.report_manager', $config['limits']['report_manager']);
        $container->setParameter('chatea_client.limits.photo_manager', $config['limits']['photo_manager']);
        $container->setParameter('chatea_client.limits.photo_album_manager', $config['limits']['photo_album_manager']);
        $container->setParameter('chatea_client.limits.photo_vote_manager', $config['limits']['photo_vote_manager']);
        $container->setParameter('chatea_client.limits.affiliate_manager', $config['limits']['affiliate_manager']);
        $container->setParameter('chatea_client.limits.city_manager', $config['limits']['city_manager']);
        $container->setParameter('chatea_client.limits.client_manager', $config['limits']['client_manager']);
        $container->setParameter('chatea_client.limits.outstanding_manager', $config['limits']['outstanding_manager']);
        $container->setParameter('chatea_client.app_auth.client_id', $config['app_auth']['client_id']);
        $container->setParameter('chatea_client.app_id', $config['app_id']);
        $container->setParameter('chatea_client.app_auth.secret', $config['app_auth']['secret']);
        $container->setParameter('chatea_client.api_endpoint', $config['api_endpoint']);
        $container->setParameter('chatea_client.api_request_allow', $config['api_request_allow']);
        $container->setParameter('chatea_client.authenticate_client_as_guest', $config['authenticate_client_as_guest']);

        if($config['filestore']['file_directory'] == 'default'){
            $config['filestore']['file_directory'] = $container->getParameter('kernel.cache_dir');
        }
        $container->setParameter('chatea_client.filestore.file_directory', $config['filestore']['file_directory']);
        if($config['filestore']['file_name'] == 'default'){
            $config['filestore']['file_name'] = uniqid('chatea_client_',true);
        }
        $container->setParameter('chatea_client.filestore.file_name', $config['filestore']['file_name']);
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
        $container->setParameter('chatea_client.register_with_profile',$config['register_with_profile']);

        if(array_key_exists('languages',$config) && array_key_exists('dir',$config['languages'])){
            if($config['languages']['dir'] == null){
                $container->setParameter('chatea_client.languages_dir', __DIR__.'/../Resources/config');
            }else{
                $container->setParameter('chatea_client.languages_dir', $config['languages']['dir']);
            }
        }

        $container->setParameter('chatea_client.countries_file', __DIR__.'/../Resources/config/countries.json');
        $container->setParameter('chatea_client.languages_file', 'languages.yml');
        $container->setParameter('chatea_client.languages_header', 'languages');
    }
}
