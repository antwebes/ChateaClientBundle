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
        $rootNode = $treeBuilder->root('antwebes_chateaclient');
        $rootNode->children()
        	->arrayNode('http_client')
        	     ->addDefaultsIfNotSet()
        		->children()
        			->scalarNode('server_endpoint')
        				->defaultValue(IHttpClient::SERVER_ENDPOINT)
        				->end()
        	   		->scalarNode('token_endpoint')	
	        	   		->defaultValue(IHttpClient::TOKEN_ENDPOINT)
	    	    	    ->end()
	    	     ->end()
	    	 ->end()  
	    	 ->arrayNode('http_error')
	    	 	->addDefaultsIfNotSet()
	    	 	->children()
			    	 ->scalarNode('format')
			    	 ->defaultValue('html')
			    	 ->end()	    	 
	    	 	->end()
	    	 ->end()	    	 
	     ->end(); 
        return $treeBuilder;
    }
}