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

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;
use Ant\Bundle\ChateaClientBundle\Api\Util\Command;
use Ant\Bundle\ChateaClientBundle\Manager\UserManager;
use Ant\Bundle\ChateaClientBundle\Tests\ApiJsonResponses\ApiResponses;

/**
 * Class UserManagerTest
 *
 * @package Ant\Bundle\ChateaClientBundle\Tests\Manager
 */
class UserManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testSearchUserByNamePaginated()
    {
        $mockApiManager = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager')
            ->disableOriginalConstructor()
            ->getMock();

        $apiResponses = new ApiResponses();

        $command = new Command('showUsers',array('filters' => 'partial_name=foo','order'=>null,'limit'=>null,'offset'=>null));

        $mockApiManager->method('execute')
            ->with( $command)
            ->willReturn($apiResponses->getUserWithFilterPartialName());

        $userManager =  new UserManager($mockApiManager);
        $userCollection = $userManager->searchUserByNamePaginated('foo');
        $this->assertInstanceOf('Ant\Bundle\ChateaClientBundle\Api\Util\Pager', $userCollection);
    }

    /**
     * @test
     */
    public function testFindOutstandingUsers()
    {
        $mockApiManager = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager')
            ->disableOriginalConstructor()
            ->getMock();

        $apiResponses = new ApiResponses();

        $command = new Command('showUsers', array('filters' => 'outstanding=1', 'order' => null));

        $mockApiManager->method('execute')
            ->with( $command)
            ->willReturn($apiResponses->getUserWithFilterPartialName());

        $userManager =  new UserManager($mockApiManager);
        $userCollection = $userManager->findOutstandingUsers();
    }


    /**
     * @test
     */
    public function testFindOutstandingUsersWithFilters()
    {
        $mockApiManager = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager')
            ->disableOriginalConstructor()
            ->getMock();

        $apiResponses = new ApiResponses();

        $command = new Command('showUsers', array('filters' => 'country=US,gender=Male,language=en,outstanding=1', 'order' => null));

        $mockApiManager->method('execute')
            ->with( $command)
            ->willReturn($apiResponses->getUserWithFilterPartialName());

        $userManager =  new UserManager($mockApiManager);
        $userCollection = $userManager->findOutstandingUsers(array('country' => 'US', 'gender' => 'Male', 'language' => 'en'));
    }
}
