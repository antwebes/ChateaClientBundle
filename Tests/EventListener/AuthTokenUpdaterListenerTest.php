<?php
namespace Ant\Bundle\ChateaClientBundle\Tests\EventListener;

use Ant\Bundle\ChateaClientBundle\EventListener\AuthTokenUpdaterListener;
use Ant\Bundle\ChateaClientBundle\Security\Authentication\Annotation\APIUser;
use Doctrine\Common\Annotations\AnnotationReader;

class AuthTokenUpdaterListenerTest extends \PHPUnit_Framework_TestCase
{
    private $annotationReader;
    private $securityContext;
    private $client;
    private $authTokenUpdaterListener;
    private $accessToken = 'aie84asd989asdf88asdf99asdf99';

    public function setUp()
    {
        $this->annotationReader = new AnnotationReader();
        $this->securityContext = $this->getMockForAbstractClass('Symfony\Component\Security\Core\SecurityContextInterface');
        $this->client = $this->getMockBuilder('Ant\ChateaClient\Service\Client\ChateaGratisAppClient')
            ->disableOriginalConstructor()
            ->getMock();
        $this->authTokenUpdaterListener = new AuthTokenUpdaterListener($this->annotationReader, $this->securityContext, $this->client);
    }

    public function testTokenIsntUpdatedIfNoAnnotationIsProvided()
    {
        $this->client
            ->expects($this->never())
            ->method('updateAccessToken');

        $eventAction1 = $this->createFilterControllerEvent('ControllerWithNoAnnotation', 'action1');
        $eventAction2 = $this->createFilterControllerEvent('ControllerWithNoAnnotation', 'action2');

        $this->authTokenUpdaterListener->onKernelController($eventAction1);
        $this->authTokenUpdaterListener->onKernelController($eventAction2);
    }

    public function testTokenIsUpdatedIfMethodHasAnnotationAndUserIsLoggedIn()
    {
        $this->client
            ->expects($this->once())
            ->method('updateAccessToken')
            ->with($this->accessToken);

        $user = $this->getAuthenticatedUser();
        $this->configureSecurityToken($user);

        $eventAction1 = $this->createFilterControllerEvent('ControllerWithMethodAnnotation', 'action1');
        $eventAction2 = $this->createFilterControllerEvent('ControllerWithMethodAnnotation', 'action2');

        $this->authTokenUpdaterListener->onKernelController($eventAction1);
        $this->authTokenUpdaterListener->onKernelController($eventAction2);
    }

    public function testTokenIsUpdatedIfClassHasAnnotationAndUserIsLoggedIn()
    {
        $this->client
            ->expects($this->exactly(2))
            ->method('updateAccessToken')
            ->with($this->accessToken);

        $user = $this->getAuthenticatedUser();
        $this->configureSecurityToken($user);

        $eventAction1 = $this->createFilterControllerEvent('ControllerWithClassAnnotation', 'action1');
        $eventAction2 = $this->createFilterControllerEvent('ControllerWithClassAnnotation', 'action2');

        $this->authTokenUpdaterListener->onKernelController($eventAction1);
        $this->authTokenUpdaterListener->onKernelController($eventAction2);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function testAccessDeniedExceptionIsThrowenIfMethodHasAnnotationAndSecurityContextHasntToken()
    {
        $this->client
            ->expects($this->never())
            ->method('updateAccessToken');

        $this->configureSecurityContextWithNoToken();

        $eventAction2 = $this->createFilterControllerEvent('ControllerWithMethodAnnotation', 'action2');

        $this->authTokenUpdaterListener->onKernelController($eventAction2);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function testAccessDeniedExceptionIsThrowenIfClassHasAnnotationAndSecurityContextHasntToken()
    {
        $this->client
            ->expects($this->never())
            ->method('updateAccessToken');

        $this->configureSecurityContextWithNoToken();

        $eventAction1 = $this->createFilterControllerEvent('ControllerWithClassAnnotation', 'action1');
        $eventAction2 = $this->createFilterControllerEvent('ControllerWithClassAnnotation', 'action2');

        $this->authTokenUpdaterListener->onKernelController($eventAction1);
        $this->authTokenUpdaterListener->onKernelController($eventAction2);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function testAccessDeniedExceptionIsThrowenIfMethodHasAnnotationAndUserIsntLoggedIn()
    {
        $this->client
            ->expects($this->never())
            ->method('updateAccessToken');

        $this->configureSecurityToken();

        $eventAction2 = $this->createFilterControllerEvent('ControllerWithMethodAnnotation', 'action2');

        $this->authTokenUpdaterListener->onKernelController($eventAction2);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function testAccessDeniedExceptionIsThrowenIfClassHasAnnotationAndUserIsntLoggedIn()
    {
        $this->client
            ->expects($this->never())
            ->method('updateAccessToken');

        $user = $this->getAuthenticatedUser(false);
        $this->configureSecurityToken($user);

        $eventAction1 = $this->createFilterControllerEvent('ControllerWithClassAnnotation', 'action1');
        $eventAction2 = $this->createFilterControllerEvent('ControllerWithClassAnnotation', 'action2');

        $this->authTokenUpdaterListener->onKernelController($eventAction1);
        $this->authTokenUpdaterListener->onKernelController($eventAction2);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function testAccessDeniedExceptionIsThrowenIfMethodHasAnnotationAndUserIsLoggedInButAccessTokenExpired()
    {
        $this->client
            ->expects($this->never())
            ->method('updateAccessToken');

        $user = $this->getAuthenticatedUser(false);
        $this->configureSecurityToken($user);

        $eventAction2 = $this->createFilterControllerEvent('ControllerWithMethodAnnotation', 'action2');

        $this->authTokenUpdaterListener->onKernelController($eventAction2);
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function testAccessDeniedExceptionIsThrowenIfClassHasAnnotationAndUserIsLoggedInButAccessTokenExpired()
    {
        $this->client
            ->expects($this->never())
            ->method('updateAccessToken');

        $this->configureSecurityToken();

        $eventAction1 = $this->createFilterControllerEvent('ControllerWithClassAnnotation', 'action1');
        $eventAction2 = $this->createFilterControllerEvent('ControllerWithClassAnnotation', 'action2');

        $this->authTokenUpdaterListener->onKernelController($eventAction1);
        $this->authTokenUpdaterListener->onKernelController($eventAction2);
    }

    private function createFilterControllerEvent($controllerName, $action)
    {
        $realControllerName = "Ant\Bundle\ChateaClientBundle\Tests\EventListener\\".$controllerName;
        $controller = new $realControllerName;

        $controllerData = array($controller, $action);

        $eventMock = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\FilterControllerEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $eventMock->expects($this->any())
            ->method('getController')
            ->will($this->returnValue($controllerData));

        return $eventMock;
    }

    private function configureSecurityToken($user = null)
    {
        $tokenMock = $this->getMockForAbstractClass('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');

        $tokenMock->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($user));

        $tokenMock->expects($this->any())
            ->method('isAuthenticated')
            ->will($this->returnValue($user != null));

        $this->securityContext
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($tokenMock));
    }

    private function configureSecurityContextWithNoToken()
    {
        $this->securityContext
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue(null));
    }

    private function getAuthenticatedUser($isCredentialsNonExpired = true)
    {
        $userMock = $this->getMockBuilder('Ant\Bundle\ChateaSecureBundle\Security\User\User')
            ->disableOriginalConstructor()
            ->getMock();

        $userMock->expects($this->any())
            ->method('getAccessToken')
            ->will($this->returnValue($this->accessToken));

        $userMock->expects($this->any())
            ->method('isCredentialsNonExpired')
            ->will($this->returnValue($isCredentialsNonExpired));

        return $userMock;
    }
}

class ControllerWithNoAnnotation
{
    public function action1()
    {
        
    }

    public function action2()
    {
        
    }
}

class ControllerWithMethodAnnotation
{
    public function action1()
    {
        
    }

    /**
     * @APIUser
     */
    public function action2()
    {
        
    }
}

/**
  * @APIUser
  */
class ControllerWithClassAnnotation
{
    public function action1()
    {
        
    }

    public function action2()
    {
        
    }
}