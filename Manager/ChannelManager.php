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
        $slug           = array_key_exists('slug',$item)?$item['slug']:'not-slug';
        $channel_type   = array_key_exists('channel_type',$item)?$item['channel_type']['name']:'not-channel-type';
        $title          = array_key_exists('title',$item)?$item['title']:'';
        $description    = array_key_exists('description',$item)?$item['description']:'';
        $owner_id       = array_key_exists('owner',$item)?$item['owner']['id']: null;
        $owner_name     = array_key_exists('owner',$item)?$item['owner']['username']: null;
        $parent_id      = null;
        $channel = new Channel($id,$name,$slug,$channel_type,$title,$description,$owner_id,$owner_name,$parent_id);

        if(isset($item['owner']) && isset($item['owner']['email'])){
            $owner = $this->get('UserManager')->hydrate($item['owner']);
            $channel->setOwner($owner);
        }

        if(isset($item['fans'])){
            $fans = $this->mapUsers($item['fans']);
            $channel->setFans($fans);
        }

        if(isset($item['moderators'])){
            $moderators = $this->mapUsers($item['moderators']);
            $channel->setModerators($moderators);
        }

        return $channel;
    }

    private function mapUsers($usersMap)
    {
        $userManager = $this->get('UserManager');

        $userMapper = function($map) use ($userManager){
            return $userManager->hydrate($map);
        };

        return array_map($userMapper, $usersMap);
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

    public function findAll($page = 1, array $filters = null, $limit = null, array $order = null)
    {
		if (!$limit) $limit = $this->limit;
		
		$command = new Command('showChannels',array('filter' => $filters, 'order' => $order));
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
    	$userManager = $this->get('UserManager');
    	$user = $userManager->findById($user_id);

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

    }
}