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

use Ant\Bundle\ChateaClientBundle\Api\Model\UserProfile;
use Ant\Bundle\ChateaClientBundle\Manager\UserProfileManager;
use Ant\Bundle\ChateaClientBundle\Tests\ApiJsonResponses\ApiResponses;
use Ant\ChateaClient\Client\ApiException;

/**
 * Class UserProfileManagerTest
 *
 * @package Ant\Bundle\ChateaClientBundle\Tests\Manager
 */
class UserProfileManagerTest extends \PHPUnit_Framework_TestCase
{
    private $mockApiManager;

    private $userProfileManager;

    protected function setUp()
    {
        parent::setUp();

        $this->mockApiManager = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->userProfileManager =  new UserProfileManager($this->mockApiManager);
    }

    /**
     * @test
     */
    public function testFindById()
    {
        $apiResponses = new ApiResponses();

        $this->mockApiManager->method('showUserProfile')
            ->with(299)
            ->willReturn($apiResponses->findUserProfileById());

        $userProfile = $this->userProfileManager->findById(299);

        $this->assertTrue($userProfile instanceof UserProfile);
    }

    /**
     * @test
     */
    public function testFindByIdNotFound()
    {
        $ex = new ApiException('ss', 404, new \Exception());

        $this->mockApiManager->method('showUserProfile')
            ->with(11001100)
            ->will($this->throwException($ex));

        $channel = $this->userProfileManager->findById(11001100);
        $this->assertTrue($channel === null);
    }

    /**
     * @test
     */
    public function testFindByIdWiApiExecption()
    {
        $ex = new ApiException('ss', 500, new \Exception());

        $this->mockApiManager->method('showUserProfile')
            ->with(11001100)
            ->will($this->throwException($ex));

        try{
            $channel = $this->userProfileManager->findById(11001100);
        }catch(ApiException $e){
            return;
        }

        $this->fail('Expected an ApiException');
    }
}
