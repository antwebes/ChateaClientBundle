<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ant3
 * Date: 26/09/13
 * Time: 11:16
 * To change this template use File | Settings | File Templates.
 */

namespace Ant\Bundle\ChateaClientBundle\Model;
use Ant\Bundle\ChateaClientBundle\Api\Repository\ApiRepository;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;

class User implements BaseModel
{

    /**
     * Repository class name
     */
    const REPOSITORY_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Repositories\\UserRepository';

    static $repository;

    public static function  setRepository(ApiRepository $repository)
    {
        self::$repository = $repository;
    }
    public static function  getRepository()
    {
        return self::$repository;
    }

    /**
     * The value for the id field.
     * @var        int
     */
    private $id = 0;


    /**
     * The value for the profile_id field.
     * @var        int
     */
    private $oProfile = NULL;

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
     * @var string
     * @NotBlank()
     * @Length(min= 4, max= 18)
     */
    protected $password;

    /**
     * The value for the enabled field.
     * @var        boolean
     */
    protected $enabled = true;
    /**
     * The value for the last_login field.
     * @var        string
     */
    protected $last_login;

    function __construct($id = 0, $username = '', $email ='', $last_login = null, $enabled = true)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = '';
        $this->last_login = $last_login;
        $this->enabled = $enabled;
        $this->oProfile = null;
    }

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
    /**
     * @param string $plainPassword
     */
    public function setPassword($plainPassword)
    {
        $this->password = $plainPassword;
    }
    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }


    /**
     * @param string $last_login
     */
    public function setLastLogin($last_login)
    {
        $this->last_login = $last_login;
    }

    /**
     * @return string
     */
    public function getLastLogin()
    {
        return $this->last_login;
    }

    public function getProfile()
    {
        if($this->oProfile === null && ($this->id !== null)){
            $this->setProfile(self::getRepository()->findProfile($this->id));
        }
        return $this->oProfile;
    }

    public function setProfile(UserProfile $v = null)
    {
        $this->oProfile = $v;

        return $this;
    }

    public  function getChannels()
    {
        return self::getRepository()->findChannlesCreated($this->id);
    }

    public  function getChannelsFan()
    {
        return self::getRepository()->findChannlesFan($this->id);
    }

    public function getBlockedUsers()
    {
        return self::getRepository()->findBlockedUsers($this->id);
    }
    public function getPhotos()
    {
        return self::getRepository()->findPhotos($this->id);
    }
    public function getVisit()
    {
        return self::getRepository()->findVisit($this->id);
    }
    public function getFriends()
    {
        return self::getRepository()->findFriends($this->id);
    }

    public function __toString()
    {
        return $this->username;
    }


}