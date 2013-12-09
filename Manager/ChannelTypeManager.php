<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Collection\ApiCollection;
use Ant\Bundle\ChateaClientBundle\Api\Model\ChannelType;
use Ant\Bundle\ChateaClientBundle\Api\Query\Filter\ApiFilter;

class ChannelTypeManager extends BaseManager implements ManagerInterface 
{
    protected $limit;

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }
    public function getLimit()
    {
        return $this->limit;
    }

    public function hydrate(array $item = null)
    {
        $name   = array_key_exists('name',$item)?$item['name']:'not-name';
        $id     = array_key_exists('_links',$item) && array_key_exists('channelsType',$item['_links'])?substr($item['_links']['channelsType']['href'],strlen($item['_links']['channelsType']['href'])-1):null;
        return new ChannelType($name, $id);
    }

    public function getModel()
    {
        return 'Ant\Bundle\ChateaClientBundle\Api\Model\ChannelType';
    }

    public function findById($id)
    {
        throw new \Exception("This method do not supported yet");
    }


    public function findAll($page = 1, array $filters = null, $limit= null, array $order = null)
    {
        $array_data = $this->getManager()->showChannelsTypes();
        $data = array_key_exists('resources',$array_data)?$array_data['resources']: array();
        $collection = new ApiCollection($array_data['total'],$array_data['page'],$array_data['limit']);
        foreach($data as $item )
        {
            $channelType = $this->hydrate($item);
            $collection->add($channelType);
        }
        return $collection;
    }

    public function save(&$object)
    {
        throw new \Exception("this method is not avaliable");
    }


    public function update(&$object)
    {
        throw new \Exception("this method is not avaliable");
    }


    public function delete($object_id)
    {
        throw new \Exception("this method is not avaliable");
    }
}