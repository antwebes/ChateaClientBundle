<?php
 /*
 * This file (User.php) is part of the adminchatea project.
 *
 * 2013 (c) Ant-Web S.L.  
 * Created by Javier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 4/12/13 - 8:28
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use Ant\Bundle\ChateaClientBundle\Api\Model\BaseModel;
use Ant\Bundle\ChateaClientBundle\Manager\AffiliateManager;

/**
 * Class Affiliate
 * @package Ant\Bundle\ChateaClientBundle\Api\Model
 */
class Affiliate implements BaseModel
{
    /**
     * Manager class name
     */
    const MANAGER_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Manager\\AffiliateManager';

    static $manager;


    public static function  setManager($manager)
    {
        if ($manager instanceof AffiliateManager){
            self::$manager = $manager;
        }else throw new \Exception("Affiliate need a manager instanceof AffiliateManager");
    }

    static function  getManager()
    {
        return static::$manager;
    }

    /**
     * @var string
     */
    protected $name;

     /**
     * @var string
     */
    protected $id;

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $nif;

    /**
     * @var string
     */
    private $email;

    /**
     * Get id
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set id
     * @param string $id
     */
    public function setId($id)
    {
       	if ($id !== null && is_numeric($id)) {
            $id = (int) $id;
        }
        $this->id = $id;

        return $this;
    }

    /**
     * Set name
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getNif()
    {
        return $this->nif;
    }

    /**
     * @param string $nif
     */
    public function setNif($nif)
    {
        $this->nif = $nif;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}