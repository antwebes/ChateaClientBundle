<?php

namespace Ant\Bundle\ChateaClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\ArrayNode;
use Doctrine\Tests\ORM\Functional\DefaultValueAddress;
use Ant\ChateaClient\Http\IHttpClient;

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
         $rootNode = $treeBuilder->root('chatea_client');         
         $rootNode->children()
 	    	 ->arrayNode('http_error')
 	    	 	->addDefaultsIfNotSet()
 	    	 	->children()
 			    	 ->scalarNode('format')->defaultValue("html")->end()	    	 
 	    	 	->end()
 	    	 ->end()	    	 
 	     ->end();  	     
        return $treeBuilder;
    }
}