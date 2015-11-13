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

use Ant\Bundle\ChateaClientBundle\Api\Model\Photo;
use Ant\Bundle\ChateaClientBundle\Api\Util\Command;
use Ant\Bundle\ChateaClientBundle\Manager\PhotoManager;
use Ant\Bundle\ChateaClientBundle\Tests\ApiJsonResponses\ApiResponses;
use Ant\ChateaClient\Client\ApiException;

/**
 * Class PhotoManagerTest
 *
 * @package Ant\Bundle\ChateaClientBundle\Tests\Manager
 */
class PhotoManagerTest extends \PHPUnit_Framework_TestCase
{
    private $mockApiManager;

    private $photoManager;

    protected function setUp()
    {
        parent::setUp();

        $this->mockApiManager = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->photoManager =  new PhotoManager($this->mockApiManager);
    }

    /**
     * @test
     */
    public function testFindById()
    {
        $apiResponses = new ApiResponses();

        $this->mockApiManager->method('showPhoto')
            ->with(506)
            ->willReturn($apiResponses->findCityById());

        $channel = $this->photoManager->findById(506);

        $this->assertTrue($channel instanceof Photo);
    }

    /**
     * @test
     */
    public function testFindByIdNotFound()
    {
        $ex = new ApiException('ss', 404, new \Exception());

        $this->mockApiManager->method('showPhoto')
            ->with(11001100)
            ->will($this->throwException($ex));

        $channel = $this->photoManager->findById(11001100);
        $this->assertTrue($channel === null);
    }

    /**
     * @test
     */
    public function testFindByIdWiApiExecption()
    {
        $ex = new ApiException('ss', 500, new \Exception());

        $this->mockApiManager->method('showPhoto')
            ->with(11001100)
            ->will($this->throwException($ex));

        try{
            $channel = $this->photoManager->findById(11001100);
        }catch(ApiException $e){
            return;
        }

        $this->fail('Expected an ApiException');
    }

/**
     * @test
     */
    public function findAll()
    {
        $mockApiManager = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager')
            ->disableOriginalConstructor()
            ->getMock();

        $apiResponses = new ApiResponses();

        $command = new Command('getPhotos',array('filters' => null, 'order' => null, 'limit' => null, 'offset' => null));

        $mockApiManager->method('execute')
            ->with( $command)
            ->willReturn($apiResponses->getUserWithFilterPartialName());

        $photoManager =  new PhotoManager($mockApiManager);
        $photosCollection = $photoManager->findAll();
        $this->assertInstanceOf('Ant\Bundle\ChateaClientBundle\Api\Util\Pager', $photosCollection);
    }

    /**
     * @test
     */
    public function findAllWithFilters()
    {
        $mockApiManager = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager')
            ->disableOriginalConstructor()
            ->getMock();

        $apiResponses = new ApiResponses();

        $command = new Command('getPhotos',array('filters' => 'minimum_votes_for_popular_photos=4', 'order'=> null, 'limit' => null, 'offset' => null));

        $mockApiManager->method('execute')
            ->with( $command)
            ->willReturn($apiResponses->getUserWithFilterPartialName());

        $photoManager =  new PhotoManager($mockApiManager);
        $photosCollection = $photoManager->findAll(1, array('minimum_votes_for_popular_photos' => 4));
        $this->assertInstanceOf('Ant\Bundle\ChateaClientBundle\Api\Util\Pager', $photosCollection);
    }

    /**
     * @test
     */
    public function findAllWithFiltersAndOrders()
    {
        $mockApiManager = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager')
            ->disableOriginalConstructor()
            ->getMock();

        $apiResponses = new ApiResponses();

        $command = new Command('getPhotos',array('filters' => 'minimum_votes_for_popular_photos=4', 'order'=> 'score=asc', 'limit' => null, 'offset' => null));

        $mockApiManager->method('execute')
            ->with( $command)
            ->willReturn($apiResponses->getUserWithFilterPartialName());

        $photoManager =  new PhotoManager($mockApiManager);
        $photosCollection = $photoManager->findAll(1, array('minimum_votes_for_popular_photos' => 4), null, array('score' => 'asc'));
        $this->assertInstanceOf('Ant\Bundle\ChateaClientBundle\Api\Util\Pager', $photosCollection);
    }
}
