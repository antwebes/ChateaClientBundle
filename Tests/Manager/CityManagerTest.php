<?php

/*
 * This file is part of the  chatBoilerplate package.
 *
 * (c) Ant web <ant@antweb.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ant\Bundle\ChateaClientBundle\Tests\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Model\City;
use Ant\Bundle\ChateaClientBundle\Manager\CityManager;
use Ant\Bundle\ChateaClientBundle\Tests\ApiJsonResponses\ApiResponses;
use Ant\ChateaClient\Client\ApiException;

/**
 * Class CityManagerTest
 *
 * @package Ant\Bundle\ChateaClientBundle\Tests\Manager
 */
class CityManagerTest extends \PHPUnit_Framework_TestCase
{
    private $mockApiManager;

    private $cityManager;

    protected function setUp()
    {
        parent::setUp();

        $this->mockApiManager = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->cityManager =  new CityManager($this->mockApiManager);
    }

    /**
     * @test
     */
    public function testFindById()
    {
        $apiResponses = new ApiResponses();

        $this->mockApiManager->method('getCity')
            ->with(302)
            ->willReturn($apiResponses->findCityById());

        $channel = $this->cityManager->findById(302);

        $this->assertTrue($channel instanceof City);
    }

    /**
     * @test
     */
    public function testFindByIdNotFound()
    {
        $ex = new ApiException('ss', 404, new \Exception());

        $this->mockApiManager->method('getCity')
            ->with(11001100)
            ->will($this->throwException($ex));

        $channel = $this->cityManager->findById(11001100);
        $this->assertTrue($channel === null);
    }

    /**
     * @test
     */
    public function testFindByIdWiApiExecption()
    {
        $ex = new ApiException('ss', 500, new \Exception());

        $this->mockApiManager->method('getCity')
            ->with(11001100)
            ->will($this->throwException($ex));

        try{
            $channel = $this->cityManager->findById(11001100);
        }catch(ApiException $e){
            return;
        }

        $this->fail('Expected an ApiException');
    }
}
