<?php
 /*
 * This file (Region.php) is part of the socialChatea project.
 *
 * 2014 (c) Ant-Web S.L.  
 * Created by Javier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 10/01/14 - 8:57
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

/**
 * Class Region
 * @package Ant\Bundle\ChateaClientBundle\Api\Model
 */
class Region implements BaseModel
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
    const MANAGER_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Manager\\RegionManager';

    /**
     * @var Country
     */
    private $country;
    private $name;
    private $code;

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
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
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->name;
    }
} 