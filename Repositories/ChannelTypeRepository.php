<?php

namespace Ant\Bundle\ChateaClientBundle\Repositories;

use Ant\Bundle\ChateaClientBundle\Api\Repository\ApiRepository;
use Ant\Bundle\ChateaClientBundle\Model\ChannelType;
use Ant\Common\Collections\ArrayCollection;

class ChannelTypeRepository  extends  ApiRepository
{

    /**
     * Tells the ObjectManager to make an instance managed and persistent.
     *
     * The object will be entered into the server.
     *
     *
     * @param object $object The instance to make managed and persistent.
     *
     * @return void
     */
    public function save(&$object)
    {
        throw new \Exception("This method do not supported yet");
    }

    /**
     * Tells the ObjectManager to make an instance managed and updated.
     *
     * The object will be entered into the server.
     *
     *
     * @param object $object The instance to make managed and persistent.
     *
     * @return void
     */
    public function update(&$object)
    {
        throw new \Exception("This method do not supported yet");
    }

    /**
     * Removes an object instance.
     *
     * A removed object will be removed from the server.
     *
     * @param number $object_id The object instance to remove.
     *
     * @return void
     */
    public function delete($object_id)
    {
        throw new \Exception("This method do not supported yet");
    }

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string
     */
    public function getClassName()
    {
        return "Ant\\Bundle\\ChateaClientBundle\\Repositories\\ChannelTypeRepository";
    }

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param number $id The identifier.
     *
     * @return object The object.
     */
    public function find($id)
    {
        throw new \Exception("This method do not supported yet");
    }

    /**
     * Finds all objects in the repository.
     *
     * @param int $limit
     * @param int $offset
     * @return \Ant\Common\Collections\Collection
     */
    public function findAll($limit = 0, $offset = 30)
    {
        $json_decode = json_decode($this->showChannelsTypes(),true);
        $data = array_key_exists('resources',$json_decode)?$json_decode['resources']: array();
        $collection = new ArrayCollection();
        foreach($data as $item )
        {
            $channelType = $this->hydrate($item);
            $collection->add($channelType);
        }
        return $collection;
    }


    public function hydrate(array $item)
    {

        $name           = array_key_exists('name',$item)?$item['name']:'not-name';
        $id     = array_key_exists('_links',$item) && array_key_exists('creator',$item['_links'])?substr($item['_links']['channelsType']['href'],strlen($item['_links']['channelsType']['href'])-1):null;
        return new ChannelType($name, $id);
    }
}