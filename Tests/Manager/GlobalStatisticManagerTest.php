<?php
/**
 * User: José Ramón Fernandez Leis
 * Email: jdeveloper.inxenio@gmail.com
 * Date: 23/03/16
 * Time: 13:12
 */

namespace Ant\Bundle\ChateaClientBundle\Manager\Tests;

use Ant\Bundle\ChateaClientBundle\Manager\GlobalStatisticManager;

class GlobalStatisticManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GlobalStatisticManager
     */
    private $globalStatisticManager;

    private $apiManager;

    protected function setUp()
    {
        parent::setUp();

        $this->apiManager = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->globalStatisticManager = new GlobalStatisticManager($this->apiManager);
    }

    public function testHydrate()
    {
        $data = array("num_disabled_photos" => 2);

        $globalStatistic = $this->globalStatisticManager->hydrate($data);

        $this->assertEquals(2, $globalStatistic->getNumDisabledPhotos());
    }
}