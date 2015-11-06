<?php
/**
 * User: José Ramón Fernandez Leis
 * Email: jdeveloper.inxenio@gmail.com
 * Date: 16/09/15
 * Time: 11:23
 */

namespace Ant\Bundle\ChateaClientBundle\Form\Model;

use Ant\Bundle\ChateaClientBundle\Api\Model\City;

use Symfony\Component\Validator\Constraints\NotBlank;


class EditCity 
{
    /**
     * @var string
     */
    protected $countryName;

    /**
     * @NotBlank
     * @var City
     */
    protected $city;

    /**
     * @var string
     */
    protected $cityName;

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param City $city
     */
    public function setCity(City $city = null)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * @param string $cityName
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * @param string $countryName
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;
    }
}