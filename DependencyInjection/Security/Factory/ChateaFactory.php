// <?php

// namespace Ant\Bundle\ChateaClientBundle\DependencyInjection\Security\Factory;
// use Symfony\Component\DependencyInjection\ContainerBuilder;
// use Symfony\Component\DependencyInjection\Reference;
// use Symfony\Component\DependencyInjection\DefinitionDecorator;
// use Symfony\Component\Config\Definition\Builder\NodeDefinition;
// use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
// use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;

// class ChateaFactory extends AbstractFactory 
// {

// 	public function getPosition() 
// 	{
// 		return 'pre_auth';
// 	}

// 	public function getKey() 
// 	{
// 		return 'ChateaClientAuth';
// 	}

//     /**
//      * Subclasses must return the id of a service which implements the
//      * AuthenticationProviderInterface.
//      *
//      * @param ContainerBuilder $container
//      * @param string           $id             The unique id of the firewall
//      * @param array            $config         The options array for this listener
//      * @param string           $userProviderId The id of the user provider
//      *
//      * @return string never null, the id of the authentication provider
//      */
//     protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId) 
// 	{
// 		$providerId = 'antwebes_user_authentication_provider_' . $id;
// 		$provider = new DefinitionDecorator('antwebes_user_authentication_provider');
// 		$container->setDefinition(
// 		    $providerId,
// 		    $provider
//         );
// 		return $providerId;

// 	}
// 	protected function createListener($container, $id, $config, $userProvider)
// 	{

// 	    $listenerId = $this->getListenerId();
	   
// 	    $listener = new DefinitionDecorator($listenerId);
// 	    $listener->replaceArgument(4, $id);
// 	    $listener->replaceArgument(5, new Reference($this->createAuthenticationSuccessHandler($container, $id, $config)));
// 	    $listener->replaceArgument(6, new Reference($this->createAuthenticationFailureHandler($container, $id, $config)));
// 	    $listener->replaceArgument(7, array_intersect_key($config, $this->options));
// 	    $listener->replaceArgument(8, new Reference('form.csrf_provider'));
// 	    $listener->replaceArgument(9, new Reference('antwebes_logger'));
// 	    $listener->replaceArgument(10, new Reference('event_dispatcher'));
	    
// 	    $listenerId .= '.'.$id;
// 	    $container->setDefinition($listenerId, $listener);
	   
// 	    return $listenerId;
	    
// 	}		
// 	protected function getListenerId() 
// 	{
		
// 		return "antwebes_authentication.listener";

// 	}
	
// 	public function addConfiguration(NodeDefinition $node)
// 	{
// 		parent::addConfiguration($node);
// 	}	


// 	protected function createEntryPoint($container, $id, $config, $defaultEntryPoint) 
// 	{
	    
// 		$entryPointId = 'antwebs.entry_point_' . $id;

// 		$container
// 				->setDefinition($entryPointId,
// 						new DefinitionDecorator('antwebs.entry_point'))
// 				->addArgument($config['login_path']);

// 		return $entryPointId;
		
// 	}	
	 
// }
