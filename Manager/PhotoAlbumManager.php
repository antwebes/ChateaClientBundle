<?php
 /*
 * This file (PhotoAlbumManager.php) is part of the adminchatea project.
 *
 * 2013 (c) Ant-Web S.L.  
 * Created by Javier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 12/12/13 - 9:13
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

namespace Ant\Bundle\ChateaClientBundle\Manager;
use Exception;
use Ant\Bundle\ChateaClientBundle\Api\Model\PhotoAlbum;

/**
 * Class PhotoAlbumManager
 * @package Ant\Bundle\ChateaClientBundle\Manager
 */
class PhotoAlbumManager extends BaseManager
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

    public function hydrate(array $item = null, PhotoAlbum $photoAlbum = null)
    {
        if($item == null){
            return null;
        }
        if($photoAlbum == null){
            $photoAlbum = new PhotoAlbum();
        }

        $photoAlbum->setId(array_key_exists('id',$item)?$item['id']:0);
        $photoAlbum->setTitle(array_key_exists('title',$item)?$item['title']:'');
        $photoAlbum->setDescription(array_key_exists('description',$item)?$item['description']:'');

        if(isset($item['participant']) && isset($item['participant']['id'])){
            $participant = $this->get('UserManager')->hydrate($item['participant']);
            $photoAlbum->setParticipant($participant);
        }
        return $photoAlbum;
    }
    public function getModel()
    {
        return 'Ant\Bundle\ChateaClientBundle\Api\Model\PhotoAlbum';
    }

    public function findById($album_id)
    {

        throw new Exception('this method is not enabled');
        /*
        if ($photo_album_id === null || $photo_album_id === 0 || !$photo_album_id)
        {
            return null;
        }
        return $this->hydrate($this->getManager()->showPhotoAlbum((int)$photo_album_id, $limit, $offset));
        */
    }

    public function findAll($page = 1, array $filters = null, $limit = null, array $order = null)
    {
        throw new \Exception('This method is not enabled');

        /*if (!$limit) $limit = $this->limit;

        $command = new Command('showChannels',array('filter' => $filters, 'order' => $order));
        return new Pager($this,$command, $page, $limit);*/

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

        throw new \Exception('This method do not implemented yet');
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

} 