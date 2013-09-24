<?php

namespace Ant\Bundle\ChateaClientBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;

class ChateaFactory extends AbstractFactory
{

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'ChateaClient';
    }

    /**
     * Subclasses must return the id of a service which implements the
     * AuthenticationProviderInterface.
     *
     * @param ContainerBuilder $container
     * @param string           $id             The unique id of the firewall
     * @param array            $config         The options array for this listener
     * @param string           $userProviderId The id of the user provider
     *
     * @return string never null, the id of the authentication provider
     */
    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        
        $providerId = 'antwebes_user_authentication_provider_' . $id;
        $provider = new DefinitionDecorator('antwebes_user_authentication_provider');
        $container->setDefinition($providerId, $provider);        
        return $providerId;
    }

    protected function getListenerId()
    {

        return "antwebes_authentication.listener";
    }

    protected function createEntryPoint($container, $id, $config, $defaultEntryPoint)
    {
        $entryPointId = 'antwebs.entry_point_' . $id;
        $container
                ->setDefinition($entryPointId, new DefinitionDecorator('antwebs.entry_point'))
                ->addArgument($config['login_path']);
        return $entryPointId;
    }
   
}
