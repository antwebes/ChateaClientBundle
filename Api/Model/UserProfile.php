<?php
namespace Ant\Bundle\ChateaClientBundle\Model;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiRepository;

class UserProfile implements BaseModel
{
    const SEXUAL_ORIENTATION_STRAIGHT   = 'straight';
    const SEXUAL_ORIENTATION_HOMOSEXUAL = 'homosexual';
    const SEXUAL_ORIENTATION_BISEXUAL   = 'bisexual';
    const GENDER_MAN        = 'man';
    const GENDER_WOMAN      = 'woman';
    const GENDER_OTHER      = 'other';

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
     * Repository class name
     */
    const REPOSITORY_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Repositories\\UserProfileRepository';



    private $id;
    private $about;
    /**
     * @Assert\Choice(choices = {"straight", "homosexual", "bisexual"})
     */
    private $sexualOrientation;
    /**
     * @Assert\Choice(choices = {"man", "woman", "other"})
     */
    private $gender;
    private $birthday;
    private $countVisits;
    private $youWant;
    private $updated_at;
    private $publicatedAt;


    function __construct(
        $id = 0,
        $about = '',
        $birthday = null,
        $countVisits = 0,
        $gender = '',
        $sexualOrientation = '',
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
        $this->sexualOrientation = $sexualOrientation;
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
     * @param mixed $sexualOrientation
     */
    public function setSexualOrientation($sexualOrientation)
    {
        $this->sexualOrientation = $sexualOrientation;
    }

    /**
     * @return mixed
     */
    public function getSexualOrientation()
    {
        return $this->sexualOrientation;
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

    public function __toString()
    {
        return $this->getAbout();
    }
}