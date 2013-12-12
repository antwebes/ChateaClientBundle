<?php
namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use DateTime;
use Ant\Bundle\ChateaClientBundle\Manager\PhotoVoteManager;
use Ant\Bundle\ChateaClientBundle\Api\Model\User;
use Ant\Bundle\ChateaClientBundle\Api\Model\Photo;
/**
 * Class PhotoVote
 *
 * @package Ant\Bundle\ChateaClientBundle\Model
 */
class PhotoVote implements BaseModel
{

    /**
     * Manager class name
     */
    const MANAGER_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Manager\\PhotoVoteManager';

    static $manager;

    public static function  setManager($manager)
    {
        if ($manager instanceof PhotoVoteManager){
            self::$manager = $manager;
        }else throw new \Exception("Channel need a manager instanceof PhotoAlbumManager");
    }

    public static function  getManager()
    {
        return self::$manager;
    }

    /**
     * @var User
     */
    private $oParticipant;
    /**
     * @var Photo
     */
    private $oPhoto;
    /**
     * @var int
     */
    private $score;
    /**
     * @var  DateTime
     */
    private $publicated_at;


    /**
     * @param \Ant\Bundle\ChateaClientBundle\Api\Model\User $participant
     */
    public function setParticipant(User $participant)
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
     * @param \Ant\Bundle\ChateaClientBundle\Api\Model\Photo $photo
     */
    public function setPhoto(Photo $photo)
    {
        $this->oPhoto = $photo;
    }

    /**
     * @return \Ant\Bundle\ChateaClientBundle\Api\Model\Photo
     */
    public function getPhoto()
    {
        return $this->oPhoto;
    }

    /**
     * @param \DateTime $publicated_at
     */
    public function setPublicatedAt(DateTime $publicated_at)
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

    public function __toString()
    {
        $this->score;
    }
}