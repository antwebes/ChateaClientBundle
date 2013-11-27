<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Util\Command;
use Ant\Bundle\ChateaClientBundle\Api\Util\Pager;
use Ant\Bundle\ChateaClientBundle\Api\Collection\ApiCollection;
use Ant\Bundle\ChateaClientBundle\Api\Model\Photo;

class PhotoManager extends BaseManager
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
        
        $id = array_key_exists('id',$item)?$item['id']:0;
        $title = array_key_exists('title',$item)?$item['title']:'';
        $publicatedAt = array_key_exists('publicatedAt',$item)?$item['publicatedAt']:new \DateTime('now');
        $numberVotes = array_key_exists('number_votes',$item)?$item['number_votes']:0;
        $score = array_key_exists('score',$item)?$item['score']:0;
        $path = array_key_exists('path',$item)?$item['path']:null;


        $photo = new Photo();

        $photo->setId($id);
        $photo->setTitle($title);
        $photo->setPublicatedAt($publicatedAt);
        $photo->setNumberVotes($numberVotes);
        $photo->setScore($score);
        $photo->setPath($path);

        return $photo;
    }

    public function getModel()
    {
    	return 'Ant\Bundle\ChateaClientBundle\Api\Model\Photo';
    }
    
    public function findById($channel_id)
    {
        /*if ($photo_id === null || $photo_id === 0 || !$photo_id)
        {
            return null;
        }
		
        return $this->hydrate($this->getManager()->showChannel($channel_id));*/
    }

    public function findAll($page = 1, array $filters = null, $limit = null, array $order = null)
    {
		/*if (!$limit) $limit = $this->limit;
		
		$command = new Command('showChannels',array('filter' => $filters, 'order' => $order));
		return new Pager($this,$command, $page, $limit);*/

    }

    public function save(&$object)
    {
        /*if(!($object instanceof Channel)){
            throw new \InvalidArgumentException('The parameter have been of type Channel');
        }

        $newChannel = $this->getManager()->addChanel($object->getName(),$object->getTitle(),$object->getDescription());

        $object = $this->hydrate($newChannel->toArray());*/

    }

    public function update(&$object)
    {
        /*if(!($object instanceof Channel)){
            throw new \InvalidArgumentException('The parameter have been of type Channel');
        }
        $updateChannel = $this->getManager()->updateChannel($object->getId(),$object->getName(),$object->getTitle(),$object->getDescription());

        $object = $this->hydrate($updateChannel->toArray());*/
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
        //$this->getManager()->delChannel($object_id);
    }

    /*public function findUser($user_id)
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

    }*/
}