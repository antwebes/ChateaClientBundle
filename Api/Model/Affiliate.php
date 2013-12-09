<?php
 /*
 * This file (Affiliate.php) is part of the adminchatea project.
 *
 * 2013 (c) Ant-Web S.L.  
 * Created by Javier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 5/12/13 - 17:28
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

namespace Ant\Bundle\ChateaClientBundle\Api\Model;


use Ant\Bundle\ChateaClientBundle\Manager\AffiliateManager;

class Affiliate  implements BaseModel
{

    protected $name;
    protected $host;

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

    public static function  getManager()
    {
        return self::$manager;
    }

    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getHost()
    {
        return $this->host;
    }
    public function setHost($host)
    {
        $this->host = $host;
    }

   public function __toString()
   {
       return $this->name;
   }
} 