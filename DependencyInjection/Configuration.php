<?php

namespace Ant\Bundle\ChateaClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('chatea_client');

        $rootNode
            ->children()
                ->arrayNode('api_manager')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode("api_manager")->defaultValue("Ant\Bundle\ChateaAdminBundle\Api\Persistence\ApiManager")->end()
                    ->end()
                ->end()
                ->arrayNode('limits')->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('channel_manager')->min(1)->defaultValue(10)->end()
                        ->integerNode('channel_type_manager')->min(1)->defaultValue(10)->end()
                        ->integerNode('user_manager')->min(1)->defaultValue(10)->end()
                        ->integerNode('report_manager')->min(1)->defaultValue(10)->end()
                        ->integerNode('photo_manager')->min(1)->defaultValue(10)->end()
                        ->integerNode('photo_album_manager')->min(1)->defaultValue(10)->end()
                        ->integerNode('photo_vote_manager')->min(1)->defaultValue(10)->end()
                        ->integerNode('affiliate_manager')->min(1)->defaultValue(10)->end()
                        ->integerNode('city_manager')->min(1)->defaultValue(10)->end()
                        ->integerNode('client_manager')->min(1)->defaultValue(10)->end()
                        ->integerNode('outstanding_manager')->min(1)->defaultValue(10)->end()
                    ->end()
                ->end()
                ->scalarNode("api_endpoint")->isRequired()->end()
                ->arrayNode('filestore')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('file_directory')->defaultValue('default')->end()
                        ->scalarNode('file_name')->defaultValue('default')->end()
                    ->end()
                ->end()
                ->arrayNode('app_auth')
                    ->children()
                        ->scalarNode('client_id')->end()
                        ->scalarNode('secret')->end()
                    ->end()
                ->end()
            ->scalarNode('app_id')->end()
            ->booleanNode('register_with_profile')->defaultTrue()->end()
            ->booleanNode('can_skip_register_profile')->defaultTrue()->end()
            ->booleanNode('authenticate_client_as_guest')->defaultFalse()->end()
            ->arrayNode('api_request_allow')
                ->prototype('scalar')->end()
                ->end()
            ->integerNode('visits_limit')->defaultValue(3)->end()
        ->end();


        $rootNode
            ->children()
            ->arrayNode('languages')->addDefaultsIfNotSet()->children()
                ->scalarNode('dir')->defaultNull()->end()
                ->scalarNode('file')->defaultValue('languages.yml')->end()
                ->scalarNode('header')->defaultValue('languages')->end()
                ->end()
            ->end();

        $rootNode
            ->children()
                ->scalarNode('root_route')->defaultValue('homepage')->end()
            ->end();

        $rootNode->children()
                ->arrayNode('profile_properties_to_check')
                ->defaultValue(array('gender', 'youWant', 'about', 'seeking'))
                ->prototype('scalar')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
