<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use Ant\Bundle\ChateaClientBundle\Manager\PhotoAlbumManager;
use Ant\Bundle\ChateaClientBundle\Api\Model\User;
/***
 * Class PhotoAlbum of photos
 *
 * @package Ant\Bundle\ChateaClientBundle\Model
 */
class PhotoAlbum implements BaseModel
{
    /**
     * Manager class name
     */
    const MANAGER_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Manager\\PhotoAlbumManager';

    static $manager;

    public static function  setManager($manager)
    {
        if ($manager instanceof PhotoAlbumManager){
            self::$manager = $manager;
        }else throw new \Exception("Channel need a manager instanceof PhotoAlbumManager");
    }

    public static function  getManager()
    {
        return self::$manager;
    }

    /**
     * @var int $id
     */
    private $id;
    /**
     * @var User
     */
    private $oParticipant;
    /**
     * @var string $title
     */
    private $title;
    /**
     * @var string $description
     */
    private $description;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Ant\Bundle\ChateaClientBundle\Api\Model\User $participant
     */
    public function setParticipant($participant)
    {
        $this->oParticipant = $participant;
    }

    /**
     * @return \Ant\Bundle\ChateaClientBundle\Api\Model\User
     */
    public function getParticipant()
    {
        return $this->oParticipant;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }




    public function __toString()
    {
        return $this->title;
    }
}