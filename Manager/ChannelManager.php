<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Util\Command;
use Ant\Bundle\ChateaClientBundle\Api\Util\Pager;
use Ant\Bundle\ChateaClientBundle\Api\Model\City;
use Ant\Bundle\ChateaClientBundle\Api\Model\Channel;
use Ant\Bundle\ChateaClientBundle\Api\Model\User;
use Ant\Bundle\ChateaClientBundle\Api\Model\ChannelType;

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
    public function hydrate(array $item = null, Channel $channel = null)
    {

        if($item == null){
            return null;
        }

        if($channel == null){
            $channel = new Channel();
        }

        $channel->setId(array_key_exists('id',$item)?$item['id']:0);
        $channel->setName(array_key_exists('name',$item)?$item['name']:'not-name');
        $channel->setSlug(array_key_exists('slug',$item)?$item['slug']:'not-slug');
        $channel->setIrcChannel(array_key_exists('irc_channel',$item)?$item['irc_channel']:'not-irc-channel-name');
        $channel->setTitle(array_key_exists('title',$item)?$item['title']:'');
        $channel->setDescription(array_key_exists('description',$item)?$item['description']:'');

        if(isset($item['owner']) && isset($item['owner']['id'])){
            $owner = $this->get('UserManager')->hydrate($item['owner']);
            $channel->setOwner($owner);
        }
        if(isset($item['city']) && isset($item['city']['name'])){
            $city = new City();
            $city->setName($item['city']['name']);
        }

        if(isset($item['fans'])){
            $fans = $this->mapUsers($item['fans']);
            $channel->setFans($fans);
        }
        if(isset($item['channel_type']) && isset($item['channel_type']['name'])){

            $channel->setChannelType(new ChannelType($item['channel_type']['name'], $item['channel_type']['id']));
        }
        $channel->setEnabled(array_key_exists('enabled',$item)?$item['enabled']:false);

        if(isset($item['moderators'])){
            $moderators = $this->mapUsers($item['moderators']);
            $channel->setModerators($moderators);
        }
        return $channel;
    }

    protected function mapUsers($usersMap)
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
        $updateChannel = $this->getManager()->updateChannel($object->getId(),$object->getName(),$object->getTitle(),$object->getDescription(),$object->getChannelType());


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