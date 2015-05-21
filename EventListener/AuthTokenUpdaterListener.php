<?php
namespace Ant\Bundle\ChateaClientBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Ant\ChateaClient\Service\Client\ChateaGratisAppClient;
use Ant\Bundle\ChateaSecureBundle\Security\User\User;

/**
 * Request listener that updates the HTTP client access token of OAuth2 if an action requirs to be made by the loggedin user
 * (action or class having @APIUser annotation)
 * Class AuthTokenUpdaterListener
 * @package Ant\Bundle\ChateaClientBundle\EventListener
 */
class AuthTokenUpdaterListener
{
    private $annotationReader;
    private $securityContext;
    private $client;
    private $annotationClass = 'Ant\Bundle\ChateaClientBundle\Security\Authentication\Annotation\APIUser';

    /**
     * Constructor
     * @param Reader $annotationReader
     * @param SecurityContextInterface $securityContext
     * @param ChateaGratisAppClient $client
     */
    function __construct(Reader $annotationReader, SecurityContextInterface $securityContext, ChateaGratisAppClient $client)
    {
        $this->annotationReader = $annotationReader;
        $this->securityContext = $securityContext;
        $this->client = $client;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /**
         * if the action has the APIUser annotation, check first if the user is valid and if so update the client acces token
         */
        if($this->hasApiUserAnnotation($controller)){
            $this->assertUserIsLoggedIn();
            $this->updateClientAccessToken();
        }
    }

    /**
     * Asserts that a user is logged in
     */
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

    /**
     * Checks if the action or the hole controller has an @APIUser annotation
     * @param $controller
     * @return bool
     */
    private function hasApiUserAnnotation($controller)
    {
        $object = new \ReflectionObject($controller[0]);
        $method = $object->getMethod($controller[1]);

        return $this->annotationReader->getMethodAnnotation($method, $this->annotationClass) != null ||
            $this->annotationReader->getClassAnnotation($object, $this->annotationClass) != null;
    }

    /**
     * Updates the access token of the HTTP client with the access token of the current user
     */
    private function updateClientAccessToken()
    {
        $user = $this->securityContext->getToken()->getUser();
        $this->client->updateAccessToken($user->getAccessToken());
    }
}