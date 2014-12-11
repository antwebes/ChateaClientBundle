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
    protected $host;

    /**
     * @var string
     */
    protected $confirmedUri;
    /**
     * @var string
     */
    protected $resettingUrl;

    /**
     * Get host
     * @return string
     */
    public function getHost()
    {
        return $this->host;
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
     * Set host
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
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
     * @return mixed
     */
    public function getConfirmedUri()
    {
        return $this->confirmedUri;
    }

    /**
     * @param mixed $confirmedUri
     */
    public function setConfirmedUri($confirmedUri)
    {
        $this->confirmedUri = $confirmedUri;
    }

    /**
     * @return string
     */
    public function getResettingUrl()
    {
        return $this->resettingUrl;
    }

    /**
     * @param string $resettingUrl
     */
    public function setResettingUrl($resettingUrl)
    {
        $this->resettingUrl = $resettingUrl;
    }
}