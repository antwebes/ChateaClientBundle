<?php
/*
 * This file is part of the  chatBoilerplate package.
 *
 * (c) Ant web <ant@antweb.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ant\Bundle\ChateaClientBundle\Tests\Controller;

use Ant\Bundle\ChateaClientBundle\Controller\UserController;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends \PHPUnit_Framework_TestCase
{
    private $containerMock;
    private $routerMock;
    private $userManagerMock;
    private $templatingMock;
    private $userController;

    protected function setUp()
    {
        parent::setUp();

        $this->containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->routerMock = $this->getMock('Symfony\Component\Routing\RouterInterface');
        $this->templatingMock = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->userManagerMock = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Manager\UserManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->userController = new UserController();
        $this->userController->setContainer($this->containerMock);
    }

    /**
     * @test
     */
    public function testRegistrationUserSuccessWithEnabledUserAction()
    {
        $user = $this->createUser(true);

        $this->containerMock->expects($this->at(0))
            ->method('get')
            ->with('api_users')
            ->will($this->returnValue($this->userManagerMock));

        $this->containerMock->expects($this->at(1))
            ->method('get')
            ->with('router')
            ->will($this->returnValue($this->routerMock));

        $this->containerMock->expects($this->any())
            ->method('has')
            ->will($this->returnValue(true));

        $this->routerMock->expects($this->once())
            ->method('generate')
            ->with('chatea_client_update_profile_index')
            ->will($this->returnValue('http://thehost.com/register'));

        $this->userManagerMock->expects($this->once())
            ->method('findById')
            ->with(3)
            ->will($this->returnValue($user));

        $response = $this->userController->registrationUserSuccessAction(3);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('http://thehost.com/register', $response->getTargetUrl());
    }

    /**
     * @test
     */
    public function testRegistrationUserSuccessWithNotEnabledUserAction()
    {
        $user = $this->createUser(false);

        $this->containerMock->expects($this->at(0))
            ->method('get')
            ->with('api_users')
            ->will($this->returnValue($this->userManagerMock));

//        $this->containerMock->expects($this->at(1))
//            ->method('get')
//            ->with('api_users')
//            ->will($this->returnValue($this->userManagerMock));

        $this->containerMock->expects($this->at(1))
            ->method('has')
            ->with('templating')
            ->will($this->returnValue(true));

        $this->containerMock->expects($this->at(2))
            ->method('get')
            ->with('templating')
            ->will($this->returnValue($this->templatingMock));

        $this->templatingMock->expects($this->once())
            ->method('renderResponse')
            ->will($this->returnValue(new Response('well registered', 200)));

        $this->userManagerMock->expects($this->once())
            ->method('findById')
            ->with(3)
            ->will($this->returnValue($user));

        $response = $this->userController->registrationUserSuccessAction(3);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('well registered', $response->getContent());
    }

    private function createUser($enabled)
    {
        $user = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Model\User')
            ->disableOriginalConstructor()
            ->getMock();

        $user->expects($this->any())
            ->method('isEnabled')
            ->will($this->returnValue($enabled));

        return $user;
    }
}
