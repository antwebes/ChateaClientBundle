<?php
/**
 * Created by PhpStorm.
 * User: ant4
 * Date: 23/12/14
 * Time: 13:48
 */

namespace Ant\Bundle\ChateaClientBundle\Manager;


class RealTimeManager extends BaseManager
{
    /**
     * Change the avatar to a nick
     * @param $nick
     * @param $id_avatar
     * @return array|string
     */
    public function changeAvatar($nick, $id_avatar)
    {
        return $this->getManager()->changeAvatar($nick, $id_avatar);
    }

    public function hydrate(array $item = null)
    {
        // TODO: Implement hydrate() method.
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
        // TODO: Implement getModel() method.
    }
}