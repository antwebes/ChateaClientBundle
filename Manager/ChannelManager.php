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

        $channel->setManager($this);

        $channel->setId(array_key_exists('id',$item)?$item['id']:0);
        $channel->setName(array_key_exists('name',$item)?$item['name']:'not-name');
        $channel->setSlug(array_key_exists('slug',$item)?$item['slug']:'not-slug');
        $channel->setIrcChannel(array_key_exists('irc_channel',$item)?$item['irc_channel']:'not-irc-channel-name');
        $channel->setDescription(array_key_exists('description',$item)?$item['description']:'');
        $channel->setNumberFans(array_key_exists('number_fans',$item)?$item['number_fans']:0);
        $channel->setCountVisits(array_key_exists('count_visits',$item)?$item['count_visits']:0);
        $channel->setPublicatedAt(array_key_exists('publicated_at',$item)?new \DateTime($item['publicated_at']):null);
        $channel->setChildren(array_key_exists('channels',$item)?$item['channels']:null );
        $channel->setIsExpired(array_key_exists('is_expired',$item)?$item['is_expired']:null );
        $channel->setLastVisit(array_key_exists('last_visit',$item)?new \DateTime($item['last_visit']):null );
        $channel->setExpiredAt(array_key_exists('expired_at',$item)?new \DateTime($item['expired_at']):null );
        $channel->setLanguage(array_key_exists('language',$item)?$item['language']:'' );

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
            $channelType = new ChannelType();
            $channelType->setId(array_key_exists('id', $item['channel_type'])?$item['channel_type']['id']:0);
            $channelType->setName(array_key_exists('name', $item['channel_type'])?$item['channel_type']['name']:'');

            $channel->setChannelType($channelType);
        }
        $channel->setEnabled(array_key_exists('enabled',$item)?$item['enabled']:false);

        if(isset($item['moderators'])){
            $moderators = $this->mapUsers($item['moderators']);
            $channel->setModerators($moderators);
        }
        if(isset($item['parent'])){
        	$parent = $this->hydrate($item['parent']);
        	$channel->setParent($parent);
        }

        if(isset($item['photo'])){
            $photo = $this->get('PhotoManager')->hydrate($item['photo']);
            $channel->setPhoto($photo);
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

        return $this->executeAndHydrateOrHandleApiException('showChannel', array($channel_id));
    }
    
    public function findBySlug($channel_slug)
    {
    	if ($channel_slug === null || !$channel_slug)
    	{
    		return null;
    	}

        return $this->executeAndHydrateOrHandleApiException('findBySlug', array($channel_slug));
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

        $newChannel = $this->getManager()->addChanel($object->getName(),$object->getIrcChannel(),$object->getDescription(), $object->getChannelType()->getName(), $object->getLanguage());

        $object = $this->hydrate($newChannel);

    }

    public function update(&$object)
    {
        if(!($object instanceof Channel)){
            throw new \InvalidArgumentException('The parameter have been of type Channel');
        }

        $updateChannel = $this->getManager()->updateChannel(
            $object->getId(),
            $object->getName(),
            $object->getSlug(),
            $object->getIrcChannel(),
            $object->getTitle(),
            $object->getDescription(),
            $object->getChannelType()->getName()
        );

        $object = $this->hydrate($updateChannel);
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

    /**
     * Increment the visit of a channel.
     *
     * @param number $object_id The object instance to increment.
     *
     * @return void
     */
    public function incrementVisit($object_id)
    {
        $this->getManager()->incrementChannelVisits($object_id);
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
    	
    	if($filters != null){
    		foreach($filters as $key => $value) {
    			$params[] = sprintf("%s=%s", $key, $value);
    		}
    		$filters = implode(',', $params);
    	}
    	
    	$limit  = is_null($limit) ? $this->limit : $limit;
    	
    	$userManager = $this->get('UserManager');

    	return new Pager($userManager,new Command('showChannelFans',array('channel_id'=>$channel_id, 'limit' => $limit, 'filters'=>$filters)),$page, $limit);

    }

    public function searchChannelByName($channelName)
    {
        $channels = $this->getManager()->searchChannelByName($channelName);
        $collection = array();
        if($channels != null || !empty($channelName)){
            foreach($channels as $channel){
                $collection [] = $this->hydrate($channel);
            }
        }
        return $collection;
    }

    public function addFanToChannel($fan, $channel)
    {
        $this->getManager()->addUserChannelFan($channel->getId(), $fan->getId());
    }

    public function delUserChannelFan($fan, $channel)
    {
        $this->getManager()->delUserChannelFan($channel->getId(), $fan->getId());
    }
}