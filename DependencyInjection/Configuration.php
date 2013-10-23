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

        $rootNode->children()
            ->arrayNode('limits')
                ->children()
                    ->integerNode('channel_manager')->min(1)->defaultValue('10')->end()
                    ->integerNode('user_manager')->min(1)->defaultValue('10')->end()
                ->end()
        ->end();

        return $treeBuilder;
    }
}
