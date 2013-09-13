<?php
namespace Ant\Bundle\ChateaClientBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\CompiledRoute;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ChateaClientExtension extends Extension
{
	
	/**
	 * Load the bundle extension.
	 *
	 * @param array            $configs   The config parameters
	 * @param ContainerBuilder $container The DI container
	 */
	public function load(array $configs, ContainerBuilder $container)
	{

		//TODO retirve my configs
		$configuration = new Configuration();
		$config = $this->processConfiguration($configuration, $configs);
		
	    foreach ($config as $rootKey => $configurationSettings) {
	    	foreach ($configurationSettings as $configKey => $configValue) {
	    		$container->setParameter(sprintf('antwebes_chateaclient.%s.%s', $rootKey, $configKey), $configValue);
            }
	    	
        }
        		
 		$loader = new XmlFileLoader(
        	$container,
        	new FileLocator(__DIR__.'/../Resources/config')
    	);
 	
 		$loader->load('services/parameters.xml');
        $loader->load('services/api.xml');     
        $loader->load('services/authorize.xml');
	}
}