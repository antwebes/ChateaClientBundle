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

use Ant\Bundle\ChateaClientBundle\Api\Model\Channel;
use Ant\Bundle\ChateaClientBundle\Manager\ChannelManager;
use Ant\Bundle\ChateaClientBundle\Tests\ApiJsonResponses\ApiResponses;
use Ant\ChateaClient\Client\ApiException;

/**
 * Class ChannelManagerTest
 *
 * @package Ant\Bundle\ChateaClientBundle\Tests\Manager
 */
class ChannelManagerTest extends \PHPUnit_Framework_TestCase
{
    private $mockApiManager;

    private $channelManager;

    protected function setUp()
    {
        parent::setUp();

        $this->mockApiManager = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->channelManager =  new ChannelManager($this->mockApiManager);
    }

    /**
     * @test
     */
    public function testFindById()
    {
        $apiResponses = new ApiResponses();

        $this->mockApiManager->method('showChannel')
            ->with(302)
            ->willReturn($apiResponses->findFindChannelById());

        $channel = $this->channelManager->findById(302);

        $this->assertTrue($channel instanceof Channel);
    }

    /**
     * @test
     */
    public function testFindByIdNotFound()
    {
        $ex = new ApiException('ss', 404, new \Exception());

        $this->mockApiManager->method('showChannel')
            ->with(11001100)
            ->will($this->throwException($ex));

        $channel = $this->channelManager->findById(11001100);
        $this->assertTrue($channel === null);
    }

    /**
     * @test
     */
    public function testFindByIdWiApiExecption()
    {
        $ex = new ApiException('ss', 500, new \Exception());

        $this->mockApiManager->method('showChannel')
            ->with(11001100)
            ->will($this->throwException($ex));

        try{
            $channel = $this->channelManager->findById(11001100);
        }catch(ApiException $e){
            return;
        }

        $this->fail('Expected an ApiException');
    }

    /**
     * @test
     */
    public function testFindBySlug()
    {
        $apiResponses = new ApiResponses();

        $this->mockApiManager->method('findBySlug')
            ->with('a-slug')
            ->willReturn($apiResponses->findFindChannelById());

        $channel = $this->channelManager->findBySlug('a-slug');

        $this->assertTrue($channel instanceof Channel);
    }

    /**
     * @test
     */
    public function testFindBySlugNotFound()
    {
        $ex = new ApiException('ss', 404, new \Exception());

        $this->mockApiManager->method('findBySlug')
            ->with('a-slug')
            ->will($this->throwException($ex));

        $channel = $this->channelManager->findBySlug('a-slug');
        $this->assertTrue($channel === null);
    }

    /**
     * @test
     */
    public function testFindBySlugWiApiExecption()
    {
        $ex = new ApiException('ss', 500, new \Exception());

        $this->mockApiManager->method('findBySlug')
            ->with('a-slug')
            ->will($this->throwException($ex));

        try{
            $channel = $this->channelManager->findBySlug('a-slug');
        }catch(ApiException $e){
            return;
        }

        $this->fail('Expected an ApiException');
    }
}
