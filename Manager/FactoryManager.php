<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;
use Ant\Bundle\ChateaClientBundle\Api\Persistence\ObjectManager;

class FactoryManager
{
	static private $arrayManagers = array();
    static public $managerMap = array('ChannelManager'          => '\Ant\Bundle\ChateaClientBundle\Manager\ChannelManager',
                                       'PhotoManager'            => '\Ant\Bundle\ChateaClientBundle\Manager\PhotoManager',
                                       'UserManager'             => '\Ant\Bundle\ChateaClientBundle\Manager\UserManager',
                                       'UserProfileManager'      => '\Ant\Bundle\ChateaClientBundle\Manager\UserProfileManager',
                                       'AffiliateManager'        => '\Ant\Bundle\ChateaClientBundle\Manager\AffiliateManager',
                                       'PhotoManager'            => '\Ant\Bundle\ChateaClientBundle\Manager\PhotoManager',
                                       'PhotoAlbumManager'       => '\Ant\Bundle\ChateaClientBundle\Manager\PhotoAlbumManager',
                                       'PhotoVoteManager'        => '\Ant\Bundle\ChateaClientBundle\Manager\PhotoVoteManager',
                                       'CityManager'             => '\Ant\Bundle\ChateaClientBundle\Manager\CityManager',
                                       'CountryManager'          => '\Ant\Bundle\ChateaClientBundle\Manager\CountryManager',
                                       'ClientManager'           => '\Ant\Bundle\ChateaClientBundle\Manager\ClientManager',
                                       'OutstandingEntryManager' => '\Ant\Bundle\ChateaClientBundle\Manager\OutstandingEntryManager',
                                       'GlobalStatisticManager'  => '\Ant\Bundle\ChateaClientBundle\Manager\GlobalStatisticManager'
                                );
	
	/**
	 * get or create manager
	 */
	static public function get($apiManager, $name)
	{
		if(!array_key_exists($name,self::$managerMap)){
            throw new \InvalidArgumentException('Error in Factory Manager. This manager ['.$name.']is not supported');
        }
        $className = self::$managerMap[$name];
        return self::factory($apiManager, $className);
	}

    static public function factory(ObjectManager $apiManager, $manager_class_name)
    {

        if(array_key_exists($manager_class_name,self::$arrayManagers)){
            return self::$arrayManagers[$manager_class_name];
        }
        $manager = new $manager_class_name($apiManager);
        self::$arrayManagers[$manager_class_name] = $manager;
        $model = $manager->getModel();
        $model::setManager($manager);
        return $manager;
    }

    static public function setObjectManagerInMap($key,$value){
        self::$managerMap[$key]=$value;
    }

    static public function releaseManager($name)
    {
        $className = self::$managerMap[$name];

        if(array_key_exists($className,self::$arrayManagers)){
            unset(self::$arrayManagers[$className]);
        }
    }
}