<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Model;
use DateTime;
use Ant\Bundle\ChateaClientBundle\Manager\PhotoManager;
use Ant\Bundle\ChateaClientBundle\Api\Model\PhotoAlbum;
use Ant\Bundle\ChateaClientBundle\Api\Model\User;

/**
 * Class Photo
 * @package Ant\Bundle\ChateaClientBundle\Api\Model
 */
class Photo implements BaseModel
{

    /**
     * Manager class name
     */
    const MANAGER_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Manager\\PhotoManager';

    static $manager;


    public static function  setManager($manager)
    {
        if ($manager instanceof PhotoManager){
            self::$manager = $manager;
        }else throw new \Exception("Photo need a manager instanceof PhotoManager");
    }
    public static function getManager()
    {
        return self::$manager;
    }
    /**
     * @var int the ID of photo
     */
    private $id;

    /**
     * @var User
     */
    private $oParticipant;

    /**
     * @var \Doctrine\Common\Collections\CollectionCollection $oVotes
     */
    private $oVotes;
    /**
     * @var DateTime $publicated_at
     */
    private $publicated_at;

    /**
     * @var string $path
     */
    private $path;

    /**
     * @var string $pathLarge
     */
    private $pathLarge;

    /**
     * @var string $pathMedium
     */
    private $pathMedium;

    /**
     * @var string $pathSmall
     */
    private $pathSmall;

    /**
     * @var string $pathIcon
     */
    private $pathIcon;

    /**
     * @var string $title
     */
    private $title;

    /**
     * @var int $number_votes
     */
    private $number_votes = 0;
    /**
     * @var int $score
     */
    private $score = 0;

    /**
     * @var PhotoAlbum
     */
    private $oAlbum;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * @param int $number_votes
     */
    public function setNumberVotes($number_votes)
    {
        $this->number_votes = $number_votes;
    }

    /**
     * @return int
     */
    public function getNumberVotes()
    {
        return $this->number_votes;
    }

    /**
     * @param \Ant\Bundle\ChateaClientBundle\Api\Model\PhotoAlbum $photoAlbum
     */
    public function setAlbum(PhotoAlbum $photoAlbum = null)
    {
        $this->oAlbum = $photoAlbum;
    }

    /**
     * @return \Ant\Bundle\ChateaClientBundle\Api\Model\PhotoAlbum
     */
    public function getAlbum()
    {
        return $this->oAlbum;
    }

    /**
     * @param User $participant
     */
    public function setParticipant($participant)
    {
        $this->oParticipant = $participant;
    }

    /**
     * @return mixed
     */
    public function getParticipant()
    {
        return $this->oParticipant;
    }

    /**
     * @param \Doctrine\Common\Collections\CollectionCollection $votes
     */
    public function setVotes($votes)
    {
        $this->oVotes = $votes;
    }
    public function addVote(PhotoVote $vote)
    {
        $this->oVotes[] = $vote;
    }

    /**
     * @return \Doctrine\Common\Collections\CollectionCollection of votes
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @param string $pathLarge
     */
    public function setPathLarge($pathLarge)
    {
        $this->pathLarge = $pathLarge;
    }

    /**
     * @param string $pathMedium
     */
    public function setPathMedium($pathMedium)
    {
        $this->pathMedium = $pathMedium;
    }

    /**
     * @param string $pathSmall
     */
    public function setPathSmall($pathSmall)
    {
        $this->pathSmall = $pathSmall;
    }

    /**
     * @param string $pathIcon
     */
    public function setPathIcon($pathIcon)
    {
        $this->pathIcon = $pathIcon;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param \DateTime $publicated_at
     */
    public function setPublicatedAt($publicated_at)
    {
        $this->publicated_at = $publicated_at;
    }

    /**
     * @return \DateTime
     */
    public function getPublicatedAt()
    {
        return $this->publicated_at;
    }

    /**
     * @param int $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return $this->score;
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

    public function getPathLarge()
    {
        return $this->pathLarge;
    }

    public function getPathMedium()
    {
        return $this->pathMedium;
    }

    public function getPathSmall()
    {
        return $this->pathSmall;
    }

    public function getPathIcon()
    {
        return $this->pathIcon;
    }

    public function __toString()
    {
        return $this->path;
    }
}