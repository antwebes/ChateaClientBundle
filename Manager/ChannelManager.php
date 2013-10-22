<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Util\Pager;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;
use Ant\Bundle\ChateaClientBundle\Api\Collection\ApiCollection;
use Ant\Bundle\ChateaClientBundle\Api\Model\Channel;
use Ant\Bundle\ChateaClientBundle\Api\Model\User;

class ChannelManager extends BaseManager implements ManagerInterface
{

    static public function hydrate(array $item = null)
    {

        if($item == null){
            return null;
        }
        $id             = array_key_exists('id',$item)?$item['id']:0;
        $name           = array_key_exists('name',$item)?$item['name']:'not-name';
        $url            = array_key_exists('url',$item)?$item['url']:'not-url';
        $channel_type   = array_key_exists('channel_type',$item)?$item['channel_type']['name']:'not-channel-type';
        $title          = array_key_exists('title',$item)?$item['title']:'';
        $description    = array_key_exists('description',$item)?$item['description']:'';
        $creator_id     = array_key_exists('_links',$item) && array_key_exists('creator',$item['_links'])?substr($item['_links']['creator']['href'],strlen($item['_links']['creator']['href'])-1):null;
        $parent_id      = array_key_exists('_links',$item) && array_key_exists('parent',$item['_links'])?substr($item['_links']['parent']['href'],strlen($item['_links']['parent']['href'])-1):null;
        return new Channel($id,$name,$url,$channel_type,$title,$description,$creator_id,$parent_id);
    }

    public function findById($channel_id)
    {
        if ($channel_id === null || $channel_id === 0 || !$channel_id)
        {
            return null;
        }

        return $this->hydrate($this->getManager()->showChannel($channel_id));;
    }

    public function findAll($limit=1 , $offset=0, array $filters = null)
    {
    	
        $array_data = $this->getManager()->showChannels($limit,$offset, $filters);
//         $data = array_key_exists('resources',$array_data)?$array_data['resources']: array();
        ld($array_data);
        $pager = new Pager($this->getManager(), $array_data);
        ld($pager->getResources());
        ldd($pager);
//         $array_data['total'],$array_data['page'],$array_data['limit']);

        foreach($data as $item ){
            $collection->add($this->hydrate($item));
        }
        return $collection;
    }
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
        if(!($object instanceof Channel)){
            throw new \InvalidArgumentException('The parameter have been of type Channel');
        }

        $newChannel = $this->getManager()->addChanel($object->getName(),$object->getTitle(),$object->getDescription());

        $object = $this->hydrate($newChannel->toArray());

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
        if(!($object instanceof Channel)){
            throw new \InvalidArgumentException('The parameter have been of type Channel');
        }
        $updateChannel = $this->getManager()->updateChannel($object->getId(),$object->getName(),$object->getTitle(),$object->getDescription());

        $object = $this->hydrate($updateChannel->toArray());
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
        $this->getManager()->delChannel($object_id);
    }

    public function findUser($user_id)
    {

//         $userRepository = $this->getManager(get_class(new User()));

        $user = $this->getManager()->showUser($user_id);
        
        return $user;
    }

    public function findFans($channel_id)
    {

        if ($channel_id === null || $channel_id === 0 || !$channel_id)
        {
            return null;
        }

        $array_data = $this->getManager()->showChannelFans($channel_id);

        $data = array_key_exists('resources',$array_data)?$array_data['resources']: array();

        $total = array_key_exists('total',$array_data)?$array_data['total']:0;
        $page = array_key_exists('page',$array_data)?$array_data['page']:1;
        $limit = array_key_exists('limit',$array_data)?$array_data['limit']:30;

        $collection = new ApiCollection($total,$page,$limit);

        foreach($data as $item )
        {
            $user = UserManager::hydrate($item);
            $collection->add($user);
        }
        return $collection;

    }
}