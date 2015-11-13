<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Util\Command;
use Ant\Bundle\ChateaClientBundle\Api\Util\Pager;
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
    public function hydrate(array $item = null, $photo = null)
    {

        if($item == null){
            return null;
        }
        if($photo == null){
            $photo = new Photo();
        }

        $photo->setId(array_key_exists('id',$item)? $item['id'] : 0);
        $photo->setTitle(array_key_exists('title',$item)?$item['title']:'');
        $photo->setPublicatedAt(array_key_exists('publicated_at',$item)? new \DateTime($item['publicated_at']):new \DateTime('now'));
        $photo->setNumberVotes(array_key_exists('number_votes',$item)?$item['number_votes']:0);
        $photo->setScore(array_key_exists('score',$item)?$item['score']:0);
        $photo->setPath(array_key_exists('path',$item)?$item['path']:null);
        $photo->setPathLarge(array_key_exists('path_large',$item)?$item['path_large']:null);
        $photo->setPathMedium(array_key_exists('path_medium',$item)?$item['path_medium']:null);
        $photo->setPathSmall(array_key_exists('path_small',$item)?$item['path_small']:null);
        $photo->setPathIcon(array_key_exists('path_icon',$item)?$item['path_icon']:null);

        if(isset($item['album']) && isset($item['album']['id'])){
            $photoAlbum = $this->get('PhotoAlbumManager')->hydrate($item['album']);
            $photo->setAlbum($photoAlbum);
        }

        if(isset($item['participant']) && isset($item['participant']['id'])){
            $participant = $this->get('UserManager')->hydrate($item['participant']);
            $photo->setParticipant($participant);
        }

        if(isset($item['votes'])){
            $votes = $item['votes'];
            foreach($votes as $vote){
                $vote = $this->get('PhotoVoteManager')->hydrate($vote);
                $photo->addVote($vote);
            }
        }
        
        return $photo;
    }

    public function getModel()
    {
    	return 'Ant\Bundle\ChateaClientBundle\Api\Model\Photo';
    }
    
    public function findById($photo_id)
    {

        if ($photo_id === null || $photo_id === 0 || !$photo_id)
        {
            return null;
        }

        return $this->executeAndHydrateOrHandleApiException('showPhoto', array((int)$photo_id));
    }
    
    public function getPhotos($filters = null)
    {    	
    	$command = new Command('getPhotos',array('filters' => $filters));
    	

    	
    	return $this->getManager()->execute($command);
    }

    public function findAll($page = 1, array $filters = null, $limit = null, array $order = null)
    {
        if($filters != null){
            foreach($filters as $key => $value) {
                $params[] = sprintf("%s=%s", $key, $value);
            }
            $filters = implode(',', $params);
        }

        if($order != null){
            foreach ($order as $key => $value) {
                $orders[] = sprintf("%s=%s", $key, $value);
            }
            $order = implode(',',$orders);
        }

        $command = new Command('getPhotos',array('filters' => $filters, 'order' => $order));
        return new Pager($this,$command, $page, $limit);
    }

    public function save(&$object)
    {
        if(!($object instanceof Photo)){
            throw new \InvalidArgumentException('The parameter have been of type Photo');
        }

        $newChannel = $this->getManager()->addPhoto(
            $object->getName(),$object->getTitle(),$object->getDescription()
        );

        $object = $this->hydrate($newChannel->toArray());

    }

    public function update(&$object)
    {
        if(!($object instanceof Photo)){
            throw new \InvalidArgumentException('The parameter have been of type Photo');
        }
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
        $this->getManager()->delPhoto($object_id);
    }

    /**
     * Report a photo
     * @param $photo
     * @param $reason
     */
    public function reportPhoto($photo, $reason)
    {
        $this->getManager()->reportPhoto($photo->getId(), $reason);
    }
}