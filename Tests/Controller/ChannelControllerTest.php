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

use Ant\Bundle\ChateaClientBundle\Controller\ChannelController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Request;

class ChannelControllerTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @test
     */
    public function tetRegisterActionAccessDeniedHttpExceptionUserNotValid()
    {
        $containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $securityContextMock = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');

        $channelController = new ChannelController();
        $channelController->setContainer($containerMock);

        $containerMock->expects($this->once())
            ->method('get')
            ->with('security.context')
            ->will($this->returnValue($securityContextMock))
        ;
        $securityContextMock->expects($this->once())
            ->method('isGranted')
            ->with('ROLE_USER_ENABLED')
            ->will($this->returnValue(false));

        $request = new Request();
        try{
            $channelController->registerAction($request);
        }catch (AccessDeniedHttpException $e){
            return;
        }

        $this->faild('The exception AccessDeniedHttpException is not throw');
    }
}
