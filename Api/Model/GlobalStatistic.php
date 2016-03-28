<?php
/**
 * User: José Ramón Fernandez Leis
 * Email: jdeveloper.inxenio@gmail.com
 * Date: 23/03/16
 * Time: 13:34
 */

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use Ant\Bundle\ChateaClientBundle\Api\Model\BaseModel;

class GlobalStatistic implements  BaseModel
{
    /**
     * Manager class name
     */
    const MANAGER_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Manager\\GlobalStatisticManager';

    static $manager;

    /**
     * @var integer
     */
    protected $numDisabledPhotos;

    static function  setManager($manager)
    {
        self::$manager = $manager;
    }

    static function  getManager()
    {
        return self::$manager;
    }

    public function __toString()
    {
        return '';
    }

    /**
     * @return int
     */
    public function getNumDisabledPhotos()
    {
        return $this->numDisabledPhotos;
    }

    /**
     * @param int $numDisabledPhotos
     */
    public function setNumDisabledPhotos($numDisabledPhotos)
    {
        $this->numDisabledPhotos = $numDisabledPhotos;
    }
}