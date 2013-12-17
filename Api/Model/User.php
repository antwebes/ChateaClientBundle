<?php
namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Doctrine\ORM\Mapping\Entity;
/**
 * Class User
 * @package Ant\Bundle\ChateaClientBundle\Api\Model
 * @Entity
 */
class User implements BaseModel
{

    /**
     * Manager class name
     */
    const MANAGER_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Manager\\UserManager';

    static $manager;

    public static function  setManager($manager)
    {
        self::$manager = $manager;
    }
    public static function  getManager()
    {
        return self::$manager;
    }

    /**
     * The value for the id field.
     * @var        int
     */
    private $id = 0;

    /**
     * The value for the username field.
     *
     * @var string
     * @NotBlank()
     * @Length(min= 4, max= 18)
     */
    private $username = '';

    /**
     * The value for the email field.
     * @var  string
     * @NotBlank()
     * @Email()
     */
    private $email = '';
    /**
     * @Regex(
     *           pattern= "/^[^a-z]|[^\w_^`\\\[\]{}]/i",
     *           htmlPattern= "/^[^a-z]|[^\w_^`\\\[\]{}]/i",
     *           match=   false,
     *           message= "Provide a valid nickname"
     *  )
     */ 
    private $nick = '';

    private $password = '';


    /**
     * The value for the profile_id field.
     * @var        int
     */
    private $oProfile = NULL;

    /**
     * The value of the profile photo
     * @var \Ant\Bundle\ChateaClientBundle\Api\Model\Photo
     */
    private $oProfilePhoto = NULL;

    /**
     * The value for channels
     */
    private $oChannels = NULL;

    /**
     * The value for favorite channels
     */
    private $oFavoriteChannels = NULL;

    /**
     * The value for channels moderated
     */
    private $oChannelsModerated = array();

    /**
     * The value for blocked users
     */
    private $oBlockedUsers = NULL;

    /**
     * The value for photos
     */
    private $oPhotos = NULL;

    /**
     * The value for visit
     */
    private $oVisit = NULL;

    /**
     * The value for friends
     */
    private $oFriends = NULL;

    /**
     * The value for city
     */
    private $oCity = NULL;

    /**
     * @var null
     */
    private $oAffiliate = null;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    public function getProfile()
    {
        if($this->oProfile === null && ($this->id !== null)){
            $this->setProfile(self::getManager()->findProfile($this->id));
        }
        return $this->oProfile;
    }

    public function setProfile(UserProfile $v = null)
    {
        $this->oProfile = $v;
    }

    public function getProfilePhoto()
    {
        return $this->oProfilePhoto;
    }

    public function setProfilePhoto($oProfilePhoto)
    {
        $this->oProfilePhoto = $oProfilePhoto;
    }

    public  function getChannels()
    {
        if($this->oChannels === null){
            $this->oChannels = self::getManager()->findChannelsCreated($this->id);
        }

        return $this->oChannels;
    }

    public function setChannels($oChannels)
    {
        $this->oChannels = $oChannels;
    }

    /**
     * @deprecated
     */
    public  function getFavoriteChannels()
    {
        if($this->oFavoriteChannels === null){
            $this->oFavoriteChannels = self::getManager()->findFavoriteChannels($this->id);
        }

        return $this->oFavoriteChannels;
    }

    public  function getChannelsFan()
    {
        if($this->oFavoriteChannels === null){
            $this->oFavoriteChannels = self::getManager()->findFavoriteChannels($this->id);
        }

        return $this->oFavoriteChannels;
    }

    public function setChannelsFan($oFavoriteChannels)
    {
        $this->oFavoriteChannels = $oFavoriteChannels;
    }

    public function getChannelsModerated()
    {
        return $this->oChannelsModerated;
    }

    public function setChannelsModerated($oChannelsModerated)
    {
        $this->oChannelsModerated = $oChannelsModerated;
    }

    public function getCity()
    {
        return $this->oCity;
    }

    public function setCity($oCity)
    {
        $this->oCity = $oCity;
    }

    public function getPhotos()
    {
        if($this->oPhotos === null){
            $this->oPhotos = self::getManager()->findPhotos($this->id);
        }

        return $this->oPhotos;
    }

    public function getVisit()
    {
        if($this->oVisit === null){
            $this->oVisit = self::getManager()->findVisit($this->id);
        }

        return $this->oVisit;
    }

    public function getFriends()
    {
        if($this->oFriends === null){
            $this->oFriends = self::getManager()->findFriends($this->id);
        }

        return $this->oFriends;
    }

    public function getBlockedUsers()
    {
        if($this->oBlockedUsers === null){
            $this->oBlockedUsers = self::getManager()->findBlockedUsers($this->id);
        }

        return $this->oBlockedUsers;
    }

    /**
     * @param string $nick
     */
    public function setNick($nick)
    {
        $this->nick = $nick;
    }

    /**
     * @return string
     */
    public function getNick()
    {
        return $this->nick;
    }

    public function getAffiliate()
    {
        return $this->oAffiliate;
    }
    public function setAffiliate(Affiliate $v)
    {
        $this->oAffiliate = $v;
    }



    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($v)
    {
        $this->password = $v;
    }    

    public function __toString()
    {
        return $this->username;
    }


}