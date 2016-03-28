<?php
/**
 * User: José Ramón Fernandez Leis
 * Email: jdeveloper.inxenio@gmail.com
 * Date: 23/03/16
 * Time: 13:22
 */

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Model\GlobalStatistic;

/**
 * Class GlobalStatisticManager
 * @package Ant\Bundle\ChateaAdminBundle\Manager
 */
class GlobalStatisticManager extends BaseManager
{
    public function hydrate(array $item = null, GlobalStatistic $globalStatistic = null)
    {
        if ($item == null) {
            return null;
        }

        if ($globalStatistic == null) {
            $globalStatistic = new GlobalStatistic();
        }

        $numDisabledPhotos = isset($item['num_disabled_photos']) ? $item['num_disabled_photos'] : 0;

        $globalStatistic->setNumDisabledPhotos($numDisabledPhotos);

        return $globalStatistic;
    }

    public function getLimit()
    {
        // TODO: Implement getLimit() method.
    }

    public function findById($id)
    {
        // TODO: Implement findById() method.
    }

    public function findAll($page = 1, array $filters = null, $limit = null, array $order = null)
    {
        // TODO: Implement findAll() method.
    }

    public function save(&$object)
    {
        // TODO: Implement save() method.
    }

    public function update(&$object)
    {
        // TODO: Implement update() method.
    }

    public function delete($object_id)
    {
        // TODO: Implement delete() method.
    }

    public function getModel()
    {
        return 'Ant\Bundle\ChateaClientBundle\Api\Model\GlobalStatistic';
    }
}