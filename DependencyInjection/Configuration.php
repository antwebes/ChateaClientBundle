<?php

namespace Ant\Bundle\ChateaClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\ArrayNode;
use Doctrine\Tests\ORM\Functional\DefaultValueAddress;

/**
 * Set the configuration of the bundle.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
/*
        $rootNode = $treeBuilder->root('antwebs_client');

        $rootNode
            ->children()
                ->enumNode('client_type')
                	 ->values(array('user_credentials', 'Auth_code','client_credentials'))                       	          	 
                	 ->defaultValue('user_credentials')
                	 ->isRequired()
                	 ->cannotBeOverwritten(true)
                	 ->info('An authorization grant is a credential representing the resource owner\'s authorization (to access its protected resources) used by the client to obtain an access token.')
                	->end()
                ->arrayNode('client_value')
                ->scalarNode('http_client')
                	->defaultValue('Ant_ChateaClient_Http_HttpClient')
                	->end()
                ->scalarNode('chatea_api')
                	->defaultValue('Ant_ChateaClient_Client_SessionStorage')
                	->end()                	
            ->end();
*/
        return $treeBuilder;
    }
}