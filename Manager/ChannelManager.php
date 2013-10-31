<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Util\Command;
use Ant\Bundle\ChateaClientBundle\Api\Util\Pager;
use Ant\Bundle\ChateaClientBundle\Api\Collection\ApiCollection;
use Ant\Bundle\ChateaClientBundle\Api\Model\Channel;
use Ant\Bundle\ChateaClientBundle\Api\Model\User;

class ChannelManager extends BaseManager
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

        if($item == null){
            return null;
        }
        $id             = array_key_exists('id',$item)?$item['id']:0;
        $name           = array_key_exists('name',$item)?$item['name']:'not-name';
        $slug            = array_key_exists('slug',$item)?$item['slug']:'not-slug';
        $channel_type   = array_key_exists('channel_type',$item)?$item['channel_type']['name']:'not-channel-type';
        $title          = array_key_exists('title',$item)?$item['title']:'';
        $description    = array_key_exists('description',$item)?$item['description']:'';
        $creator_id     = array_key_exists('_links',$item) && array_key_exists('creator',$item['_links'])?substr($item['_links']['creator']['href'],strlen($item['_links']['creator']['href'])-1):null;
        $parent_id      = array_key_exists('_links',$item) && array_key_exists('parent',$item['_links'])?substr($item['_links']['parent']['href'],strlen($item['_links']['parent']['href'])-1):null;
        return new Channel($id,$name,$slug,$channel_type,$title,$description,$creator_id,$parent_id);
    }

    public function getModel()
    {
    	return 'Ant\Bundle\ChateaClientBundle\Api\Model\Channel';
    }
    
    public function findById($channel_id)
    {
        if ($channel_id === null || $channel_id === 0 || !$channel_id)
        {
            return null;
        }
		
        return $this->hydrate($this->getManager()->showChannel($channel_id));
    }

    public function findAll($page = 1, array $filters = null, $limit = null)
    {
		if (!$limit) $limit = $this->limit;
		
		$command = new Command('showChannels',array('filter'=>$filters));
		return new Pager($this,$command, $page, $limit);

    }

    public function save(&$object)
    {
        if(!($object instanceof Channel)){
            throw new \InvalidArgumentException('The parameter have been of type Channel');
        }

        $newChannel = $this->getManager()->addChanel($object->getName(),$object->getTitle(),$object->getDescription());

        $object = $this->hydrate($newChannel->toArray());

    }

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
        $user = $userManager->showUser($user_id);
        UserManager::hydrate($user);   
        return $user;
    }

    public function findFans($channel_id, $page=1, array $filters = null, $limit= null)
    {
    	if (!$channel_id)
    	{
    		return null;
    	}
    	
    	$limit  = $limit == null ? $this->limit : $limit;
    	
    	$userManager = $this->get('UserManager');
    	
    	return new Pager($userManager,new Command('showChannelFans',array('channel_id'=>$channel_id, 'filters'=>$filters)),$page, $limit);
    	
        
        
//         $array_data = $this->getManager()->showChannelFans($channel_id);
	
//         $data = array_key_exists('resources',$array_data)?$array_data['resources']: array();

//         $total = array_key_exists('total',$array_data)?$array_data['total']:0;
//         $page = array_key_exists('page',$array_data)?$array_data['page']:1;
//         $limit = array_key_exists('limit',$array_data)?$array_data['limit']:30;

//         $collection = new ApiCollection($total,$page,$limit);

//         foreach($data as $item )
//         {
//         	$userManager = $this->get('UserManager', 25);
//             $user = $userManager->hydrate($item);
//             $collection->add($user);
//         }
//         return $collection;

    }
}