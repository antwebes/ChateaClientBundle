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
     *
     */
    private $translatedFields = array();

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
    public function getName($language = 'en')
    {
        return $this->getTranslatedField('name', $language);
    }

    /**
     * Set the name of the city
     *
     * @param string $name
     */
    public function setName($name, $language = 'en')
    {
        $this->setTranslatedField('name', $name, $language);
    }

    /**
     * Returns the province of the city
     * @return string
     */
    public function getProvince($language = 'en')
    {
        return $this->getTranslatedField('province', $language);
    }

    /**
     * Set the province of the city
     *
     * @param string $name
     */
    public function setProvince($province, $language = 'en')
    {
        $this->setTranslatedField('province', $province, $language);
    }

    /**
     * @param \Ant\Bundle\ChateaClientBundle\Api\Model\Country $country
     */
    public function setCountry($country, $language = 'en')
    {
        $this->setTranslatedField('country', $country, $language);
    }

    /**
     * @return \Ant\Bundle\ChateaClientBundle\Api\Model\Country
     */
    public function getCountry($language = 'en')
    {
        return $this->getTranslatedField('country', $language);
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
    public function setRegion($region, $language = 'en')
    {
        $this->setTranslatedField('region', $region, $language);
    }

    /**
     * @return \Ant\Bundle\ChateaClientBundle\Api\Model\Region
     */
    public function getRegion($language = 'en')
    {
        return $this->getTranslatedField('region', $language);
    }

    public function __toString()
    {
        return $this->getName();
    }

    private function setTranslatedField($field, $value, $language)
    {
        if(!isset($this->translatedFields[$language])){
            $this->translatedFields[$language] = array();
        }

        $this->translatedFields[$language][$field] = $value;
    }

    private function getTranslatedField($field, $language)
    {
        $value = $this->getTranslatedFieldOrNull($field, $language);

        if($value === null || empty($value)){ // if the field does not exist in the quiven language set language to en
            return $this->getTranslatedFieldOrNull($field, 'en');
        }

        return $value;
    }

    private function getTranslatedFieldOrNull($field, $language)
    {
        if(!(isset($this->translatedFields[$language]) && isset($this->translatedFields[$language][$field]))){
            return null;
        }

        return $this->translatedFields[$language][$field];
    }
}