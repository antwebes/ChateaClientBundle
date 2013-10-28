<?php
namespace Ant\Bundle\ChateaClientBundle\Tests\Security\Http\Logout;

use Ant\Bundle\ChateaClientBundle\Security\Http\Logout\RevokeAccessOnLogoutHanler;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

class RevokeAccessOnLogoutHanlerTest extends \PHPUnit_Framework_TestCase
{
    private $securityContext;
    private $client;
    private $revokeAccesOnLogoutHanler;
    private $accessToken = 'aie84asd989asdf88asdf99asdf99';

    public function setUp()
    {
        $this->securityContext = $this->getMockForAbstractClass('Symfony\Component\Security\Core\SecurityContextInterface');
        $this->client = $this->getMockBuilder('Ant\ChateaClient\Service\Client\ChateaGratisAppClient')
            ->disableOriginalConstructor()
            ->getMock();

        $this->revokeAccesOnLogoutHanler = new RevokeAccessOnLogoutHanler($this->securityContext, $this->client);
    }

    public function testImplementsLogoutHandlerInterface()
    {
        $this->assertTrue($this->revokeAccesOnLogoutHanler instanceof LogoutHandlerInterface);
    }

    public function testWhenTokenHasAuthenticatedApiUserRevokeTheToken()
    {
        $this->client
            ->expects($this->once())
            ->method('updateAccessToken')
            ->with($this->accessToken);
        $this->client
            ->expects($this->once())
            ->method('revokeToken');

        $user = $this->getAuthenticatedUser();
        $this->configureSecurityToken($user);
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        $token = $this->securityContext->getToken();

        $this->revokeAccesOnLogoutHanler->logout($request, $response, $token);
    }

    public function testDontTryRevokeAccessTokenWhenNoTokenIsAviable()
    {
        $this->client
            ->expects($this->never())
            ->method('updateAccessToken');
        $this->client
            ->expects($this->never())
            ->method('__call');

        $this->configureSecurityContextWithNoToken();
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        $token = $this->getMockForAbstractClass('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');

        $this->revokeAccesOnLogoutHanler->logout($request, $response, $token);
    }

    public function testDontTryRevokeAccessTokenWhenLoggedUserIsntAAPIUser()
    {
        $this->client
            ->expects($this->never())
            ->method('updateAccessToken');
        $this->client
            ->expects($this->never())
            ->method('__call');

        $user = $this->getMockForAbstractClass('Symfony\Component\Security\Core\User\UserInterface');
        $this->configureSecurityToken($user);
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        $token = $this->getMockForAbstractClass('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');

        $this->revokeAccesOnLogoutHanler->logout($request, $response, $token);
    }

    public function testDontTryRevokeAccessTokenWhenSymfonyTokenHasNoUser()
    {
        $this->client
            ->expects($this->never())
            ->method('updateAccessToken');
        $this->client
            ->expects($this->never())
            ->method('__call');

        $this->configureSecurityToken();
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        $token = $this->getMockForAbstractClass('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');

        $this->revokeAccesOnLogoutHanler->logout($request, $response, $token);
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
        $userMock = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Security\User\User')
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