<?php
namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;
use Symfony\Component\Validator\Constraints\Choice;

class UserProfile implements BaseModel
{
    const SEEKING_MEN   = 'men';
    const SEEKING_WOMAN = 'women';
    const SEEKING_BOTH   = 'both';
    const GENDER_MAN        = 'male';
    const GENDER_WOMAN      = 'female';
    const GENDER_OTHER      = 'other';

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
     * Manager class name
     */
    const MANAGER_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Manager\\UserProfileManager';



    private $id;
    private $about;
    /**
     * @Choice(choices = {"men", "women", "both"})
     */
    private $seeking;
    /**
     * @Choice(choices = {"male", "female", "other"})
     */
    private $gender;
    private $birthday;
    private $countVisits;
    private $youWant;
    private $updated_at;
    private $publicatedAt;

    /**
     * The value of the profile photo
     * @var \Ant\Bundle\ChateaClientBundle\Api\Model\Photo
     */
    private $oProfilePhoto = NULL;


    function __construct(
        $id = 0,
        $about = '',
        $birthday = null,
        $countVisits = 0,
        $gender = '',
        $seeking = '',
        $youWant = '',
        $updated_at = null,
        $publicatedAt = null
    ) {
        $this->about = $about;
        $this->birthday = $birthday;
        $this->countVisits = $countVisits;
        $this->gender = $gender;
        $this->id = $id;
        $this->publicatedAt = $publicatedAt;
        $this->seeking = $seeking;
        $this->updated_at = $updated_at;
        $this->youWant = $youWant;
    }

    /**
     * @param mixed $about
     */
    public function setAbout($about)
    {
        $this->about = $about;
    }

    /**
     * @return mixed
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $countVisits
     */
    public function setCountVisits($countVisits)
    {
        $this->countVisits = $countVisits;
    }

    /**
     * @return mixed
     */
    public function getCountVisits()
    {
        return $this->countVisits;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $publicatedAt
     */
    public function setPublicatedAt($publicatedAt)
    {
        $this->publicatedAt = $publicatedAt;
    }

    /**
     * @return mixed
     */
    public function getPublicatedAt()
    {
        return $this->publicatedAt;
    }

    /**
     * @param mixed $seeking
     */
    public function setSeeking($seeking)
    {
        $this->seeking = $seeking;
    }

    /**
     * @return mixed
     */
    public function getSeeking()
    {
        return $this->seeking;
    }


    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $youWant
     */
    public function setYouWant($youWant)
    {
        $this->youWant = $youWant;
    }

    /**
     * @return mixed
     */
    public function getYouWant()
    {
        return $this->youWant;
    }

    public function getProfilePhoto()
    {
        return $this->oProfilePhoto;
    }

    public function setProfilePhoto($oProfilePhoto)
    {
        $this->oProfilePhoto = $oProfilePhoto;
    }

    public function __toString()
    {
        return $this->getAbout();
    }
}