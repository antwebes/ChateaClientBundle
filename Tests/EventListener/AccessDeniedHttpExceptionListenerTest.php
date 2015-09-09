<?php
/*
 * This file is part of the  chatBoilerplate package.
 *
 * (c) Ant web <ant@antweb.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ant\Bundle\ChateaClientBundle\Tests\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Ant\Bundle\ChateaSecureBundle\Security\User\User;

use Ant\Bundle\ChateaClientBundle\EventListener\AccessDeniedHttpExceptionListener;


/**
 * Class AccessDeniedHttpExceptionListenerTest
 *
 * @package Ant\Bundle\ChateaClientBundle\Tests\EventListener
 */
class AccessDeniedHttpExceptionListenerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testOnKernelException()
    {
        $securityContextMock = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');
        $urlGeneratorMock = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');

        $user = new User(1,'test_user','access_token_test','refresh_token_text',false);
        $token = new UsernamePasswordToken(
            $user,
            'test_credentials',
            'test_provider',
            array('ROLE_USER','ROLE_USER_TEST')
        );

        $user->setExpiresIn(time()+3600);

        $this->securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');

        $accessDeniedHttpExceptionListener = new AccessDeniedHttpExceptionListener(
            $securityContextMock,
            $urlGeneratorMock,
            'routeIdUserNotValidated'
        );

        $event = new GetResponseForExceptionEvent($kernel,new Request(),HttpKernelInterface::SUB_REQUEST, new AccessDeniedHttpException());


        $securityContextMock ->expects($this->once())
            ->method('getToken')
            ->will($this->returnValue($token));

        $urlGeneratorMock ->expects($this->once())
            ->method('generate')
            ->with('routeIdUserNotValidated',array('userId'=>1))
            ->will($this->returnValue('http://redirect.test'));

        $accessDeniedHttpExceptionListener->onKernelException($event);


        $response = $event->getResponse();
        $this->assertEquals('302',$response->getStatusCode());
    }
}
