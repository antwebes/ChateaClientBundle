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

use Ant\Bundle\ChateaClientBundle\Api\Model\User;
use Ant\Bundle\ChateaClientBundle\Api\Util\Command;
use Ant\Bundle\ChateaClientBundle\Manager\UserManager;
use Ant\Bundle\ChateaClientBundle\Tests\ApiJsonResponses\ApiResponses;
use Ant\ChateaClient\Client\ApiException;

/**
 * Class UserManagerTest
 *
 * @package Ant\Bundle\ChateaClientBundle\Tests\Manager
 */
class UserManagerTest extends \PHPUnit_Framework_TestCase
{
    private $mockApiManager;

    private $userManager;

    protected function setUp()
    {
        parent::setUp();

        $this->mockApiManager = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->userManager =  new UserManager($this->mockApiManager);
    }

    /**
     * @test
     */
    public function testSearchUserByNamePaginated()
    {
        $apiResponses = new ApiResponses();

        $command = new Command('showUsers',array('filters' => 'partial_name=foo','order'=>null,'limit'=>null,'offset'=>null));

        $this->mockApiManager->method('execute')
            ->with( $command)
            ->willReturn($apiResponses->getUserWithFilterPartialName());

        $userCollection = $this->userManager->searchUserByNamePaginated('foo');
        $this->assertInstanceOf('Ant\Bundle\ChateaClientBundle\Api\Util\Pager', $userCollection);
    }

    /**
     * @test
     */
    public function testFindOutstandingUsers()
    {
        $apiResponses = new ApiResponses();

        $command = new Command('showUsers', array('filters' => 'outstanding=1', 'order' => null));

        $this->mockApiManager->method('execute')
            ->with( $command)
            ->willReturn($apiResponses->getUserWithFilterPartialName());

        $userCollection = $this->userManager->findOutstandingUsers();
    }


    /**
     * @test
     */
    public function testFindOutstandingUsersWithFilters()
    {
        $apiResponses = new ApiResponses();

        $command = new Command('showUsers', array('filters' => 'country=US,gender=Male,language=en,outstanding=1', 'order' => null));

        $this->mockApiManager->method('execute')
            ->with( $command)
            ->willReturn($apiResponses->getUserWithFilterPartialName());

        $userCollection = $this->userManager->findOutstandingUsers(array('country' => 'US', 'gender' => 'Male', 'language' => 'en'));
    }

    /**
     * @test
     */
    public function testFindById()
    {
        $apiResponses = new ApiResponses();

        $this->mockApiManager->method('showUser')
            ->with(302)
            ->willReturn($apiResponses->findUserById());

        $user = $this->userManager->findById(302);
        $this->assertTrue($user instanceof User);
    }

    /**
     * @test
     */
    public function testFindByIdAsArray()
    {
        $apiResponses = new ApiResponses();

        $this->mockApiManager->method('showUser')
            ->with(302)
            ->willReturn($apiResponses->findUserById());

        $user = $this->userManager->findById(302, true);
        $this->assertTrue(is_array($user));
    }

    /**
     * @test
     */
    public function testFindByIdNotFound()
    {
        $ex = new ApiException('ss', 404, new \Exception());

        $this->mockApiManager->method('showUser')
            ->with(11001100)
            ->will($this->throwException($ex));

        $user = $this->userManager->findById(11001100);
        $this->assertTrue($user === null);
    }

    /**
     * @test
     */
    public function testFindByIdWiApiExecption()
    {
        $ex = new ApiException('ss', 500, new \Exception());

        $this->mockApiManager->method('showUser')
            ->with(11001100)
            ->will($this->throwException($ex));

        try{
            $user = $this->userManager->findById(11001100);
        }catch(ApiException $e){
            return;
        }

        $this->fail('Expected an ApiException');
    }

    /**
     * @test
     */
    public function testFindByIdNotFoundAsArray()
    {
        $ex = new ApiException('ss', 404, new \Exception());

        $this->mockApiManager->method('showUser')
            ->with(11001100)
            ->will($this->throwException($ex));

        $user = $this->userManager->findById(11001100, true);
        $this->assertTrue($user === null);
    }

    /**
     * @test
     */
    public function testFindByIdWiApiExecptionAsArray()
    {
        $ex = new ApiException('ss', 500, new \Exception());

        $this->mockApiManager->method('showUser')
            ->with(11001100)
            ->will($this->throwException($ex));

        try{
            $user = $this->userManager->findById(11001100, true);
        }catch(ApiException $e){
            return;
        }

        $this->fail('Expected an ApiException');
    }
}
