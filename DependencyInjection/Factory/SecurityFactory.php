<?php 
namespace Ant\ChateaClientBundle\DependencyInjection\Factory;
 
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory;
 
class SecurityFactory extends FormLoginFactory
{
    public function getKey()
    {
        return 'webservice-login';
    }
 
    protected function getListenerId()
    {
        return 'security.authentication.listener.form';
    }
 
    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $provider = 'ecurity.authentication_provider.antwebs_chateaclient.'.$id;
        $container
            ->setDefinition($provider, new DefinitionDecorator('ecurity.authentication_provider.antwebs_chateaclient'))
            ->replaceArgument(0, new Reference($userProviderId))
            ->replaceArgument(2, $id)
        ;
 
        return $provider;
    }
}