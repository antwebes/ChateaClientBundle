<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

/**
 * Class City
 * @package Ant\Bundle\ChateaClientBundle\Api\Model
 */
class City implements BaseModel
{

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
    const MANAGER_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Manager\\CityManager';

    /**
     * @var int the unique id
     */
    private $id;
    /**
     * @var Country
     */
    private $country;
    /**
     * @var Region
     */
    private $region;
    /**
     * @var string
     */
    private $name;

    /**
     * @var string the postal code
     */
    private $postalCode;
    /**
     * Returns the name of the city
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name of the city
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param \Ant\Bundle\ChateaClientBundle\Api\Model\Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return \Ant\Bundle\ChateaClientBundle\Api\Model\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

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
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param \Ant\Bundle\ChateaClientBundle\Api\Model\Region $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return \Ant\Bundle\ChateaClientBundle\Api\Model\Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    public function __toString()
    {
        return $this->name;
    }
}