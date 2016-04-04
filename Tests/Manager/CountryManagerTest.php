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

use Ant\Bundle\ChateaClientBundle\Api\Model\Country;
use Ant\Bundle\ChateaClientBundle\Manager\CountryManager;
use Ant\Bundle\ChateaClientBundle\Tests\ApiJsonResponses\ApiResponses;
use Ant\ChateaClient\Client\ApiException;

/**
 * Class CityManagerTest
 *
 * @package Ant\Bundle\ChateaClientBundle\Tests\Manager
 */
class CountryManagerTest extends \PHPUnit_Framework_TestCase
{
    private $mockApiManager;

    private $countryManager;

    protected function setUp()
    {
        parent::setUp();

        $this->mockApiManager = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager')
            ->disableOriginalConstructor()
            ->setMethods(array('getCountry'))
            ->getMock();

        $this->countryManager = new CountryManager($this->mockApiManager);
    }

    /**
     * @test
     */
    public function testFindByCountryCode()
    {
        $apiResponses = new ApiResponses();

        $this->mockApiManager->method('getCountry')
            ->with('AF')
            ->willReturn($apiResponses->findCountryByCode());

        $country = $this->countryManager->findByCode('AF');

        $this->assertTrue($country instanceof Country);
    }

//    /**
//     * @test
//     */
//    public function testFindByIdNotFound()
//    {
//        $ex = new ApiException('ss', 404, new \Exception());
//
//        $this->mockApiManager->method('getCity')
//            ->with(11001100)
//            ->will($this->throwException($ex));
//
//        $channel = $this->cityManager->findById(11001100);
//        $this->assertTrue($channel === null);
//    }
}
