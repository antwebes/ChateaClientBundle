<?php
namespace Ant\Bundle\ChateaClientBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Ant\ChateaClient\Service\Client\ChateaGratisAppClient;
use Ant\Bundle\ChateaClientBundle\Security\User\User;

class AuthTokenUpdaterListener
{
    private $annotationReader;
    private $securityContext;
    private $client;
    private $annotationClass = 'Ant\Bundle\ChateaClientBundle\Security\Authentication\Annotation\APIUser';

    function __construct(Reader $annotationReader, SecurityContextInterface $securityContext, ChateaGratisAppClient $client)
    {
        $this->annotationReader = $annotationReader;
        $this->securityContext = $securityContext;
        $this->client = $client;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if($this->hasApiUserAnnotation($controller)){
            $this->assertUserIsLoggedIn();
            $this->updateClientAccessToken();
        }
    }

    private function assertUserIsLoggedIn()
    {
        $token = $this->securityContext->getToken();
        $isAuthenticated = $token !== null &&
                           $token->isAuthenticated() && 
                           $token->getUser() instanceof User &&
                           $token->getUser()->isCredentialsNonExpired();

        if(!$isAuthenticated){
            throw new AccessDeniedException();
        }
    }

    private function hasApiUserAnnotation($controller)
    {
        $object = new \ReflectionObject($controller[0]);
        $method = $object->getMethod($controller[1]);

        return $this->annotationReader->getMethodAnnotation($method, $this->annotationClass) != null ||
            $this->annotationReader->getClassAnnotation($object, $this->annotationClass) != null;
    }

    private function updateClientAccessToken()
    {
        $user = $this->securityContext->getToken()->getUser();
        $this->client->updateAccessToken($user->getAccessToken());
    }
}