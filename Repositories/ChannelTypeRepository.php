<?php

namespace Ant\Bundle\ChateaClientBundle\Repositories;

use Ant\Bundle\ChateaClientBundle\Api\Collection\ApiCollection;
use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiRepository;
use Ant\Bundle\ChateaClientBundle\Api\Model\ChannelType;
use Ant\Bundle\ChateaClientBundle\Api\Query\Filter\ApiFilter;

class ChannelTypeRepository  extends  ApiRepository
{

    public function save(&$object)
    {
        throw new \Exception("This method do not supported yet");
    }


    public function update(&$object)
    {
        throw new \Exception("This method do not supported yet");
    }


    public function delete($object_id)
    {
        throw new \Exception("This method do not supported yet");
    }

    public function getClassName()
    {
        return "Ant\\Bundle\\ChateaClientBundle\\Repositories\\ChannelTypeRepository";
    }


    public function find($id)
    {
        throw new \Exception("This method do not supported yet");
    }


    public function findAll($page = 1)
    {
        $json_decode = $this->showChannelsTypes()->toArray();

        $data = array_key_exists('resources',$json_decode)?$json_decode['resources']: array();
        $collection = new ApiCollection();
        foreach($data as $item )
        {
            $channelType = $this->hydrate($item);
            $collection->add($channelType);
        }
        return $collection;
    }


    public function hydrate(array $item)
    {
        $name   = array_key_exists('name',$item)?$item['name']:'not-name';
        $id     = array_key_exists('_links',$item) && array_key_exists('channelsType',$item['_links'])?substr($item['_links']['channelsType']['href'],strlen($item['_links']['channelsType']['href'])-1):null;
        return new ChannelType($name, $id);
    }
}