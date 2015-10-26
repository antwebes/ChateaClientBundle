<?php

/*
 * This file is part of the  chatBoilerplate package.
 *
 * (c) Ant web <ant@antweb.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ant\Bundle\ChateaClientBundle\Tests\Request\ParamConverter;

use Ant\Bundle\ChateaClientBundle\Api\Model\User;
use Ant\Bundle\ChateaClientBundle\Manager\FactoryManager;
use Ant\Bundle\ChateaClientBundle\Request\ParamConverter\ApiChateaParamConverter;
use Ant\ChateaClient\Client\ApiException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CityManagerTest
 *
 * @package Ant\Bundle\ChateaClientBundle\Tests\Manager
 */
class ApiChateaParamConverterTest extends \PHPUnit_Framework_TestCase
{
    private $mockApiManager;

    /**
     * @var ApiChateaParamConverter
     */
    private $apiChateaParamConverter;

    protected function setUp()
    {
        parent::setUp();

        $this->mockApiManager = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->apiChateaParamConverter =  new ApiChateaParamConverter($this->mockApiManager);
    }

    /**
     * @dataProvider getValidClasses
     */
    public function testSupports($class)
    {
        $config = new ParamConverter(array('class' => $class));
        $this->assertTrue($this->apiChateaParamConverter->supports($config));
    }

    /**
     * @dataProvider getNotValidClasses
     */
    public function testSupportsNotValidClasses($class)
    {
        $config = new ParamConverter(array('class' => $class));
        $this->assertFalse($this->apiChateaParamConverter->supports($config));
    }

    public function testApply()
    {
        FactoryManager::releaseManager('UserManager'); // we make sure we get a new instance of an UserManager

        $config = new ParamConverter(array(
            'class' => "Ant\\Bundle\\ChateaClientBundle\\Api\\Model\\User",
            'name' => 'user',
            'options' => array(
                'manager' => 'UserManager',
                'method' => 'findById',
                'methodParams' => array('id')
            )
        ));

        $parameterBag = $this->getMockBuilder('Symfony\Component\HttpFoundation\ParameterBag')
            ->disableOriginalConstructor()
            ->getMock();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('get')
            ->with('id')
            ->will($this->returnValue("1"));

        $parameterBag->expects($this->once())
            ->method('set')
            ->with('user', $this->callback(function($user){
                return $user instanceof User;
            }));

        $request->attributes = $parameterBag;

        $this->mockApiManager->expects($this->once())
            ->method('showUser')
            ->with("1")
            ->will($this->returnValue(array('id' => 1, 'username' => 'alex')));

        $this->assertTrue($this->apiChateaParamConverter->apply($request, $config));

        FactoryManager::releaseManager('UserManager'); // we make sure we get a new instance of an UserManager
    }

    public function testApplyNotAviableUser()
    {
        FactoryManager::releaseManager('UserManager'); // we make sure we get a new instance of an UserManager

        $config = new ParamConverter(array(
            'class' => "Ant\\Bundle\\ChateaClientBundle\\Api\\Model\\User",
            'name' => 'user',
            'options' => array(
                'manager' => 'UserManager',
                'method' => 'findById',
                'methodParams' => array('id')
            )
        ));

        $parameterBag = $this->getMockBuilder('Symfony\Component\HttpFoundation\ParameterBag')
            ->disableOriginalConstructor()
            ->getMock();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('get')
            ->with('id')
            ->will($this->returnValue(1));

        $parameterBag->expects($this->never())
            ->method('set');

        $request->attributes = $parameterBag;

        try{
            $this->apiChateaParamConverter->apply($request, $config);
        }catch (NotFoundHttpException $e){
            FactoryManager::releaseManager('UserManager'); // we make sure we get a new instance of an UserManager
            return;
        }

        FactoryManager::releaseManager('UserManager'); // we make sure we get a new instance of an UserManager
        $this->fail('Expected an NotFoundHttpException execption');
    }

    public function getValidClasses()
    {
        $classes = array('Affiliate', 'Channel', 'ChannelType', 'City', 'Client', 'Country',
            'OutstandingEntry', 'Photo', 'PhotoAlbum', 'PhotoVote', 'Region', 'User', 'UserProfile');

        return array_map(function($class){
            return array("Ant\\Bundle\\ChateaClientBundle\\Api\\Model\\".$class);
        }, $classes);
    }

    public function getNotValidClasses()
    {
        $classes = array('Friendship', 'Message', 'Thread', 'Report', 'UserVisit');

        return array_map(function($class){
            return array("Ant\\Bundle\\ChateaClientBundle\\Api\\Model\\".$class);
        }, $classes);
    }
}
