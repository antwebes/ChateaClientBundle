<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;

class FactoryManager
{
	static private $arrayManagers = array();
    static private $managerMap = array('ChannelManager'=>'\Ant\Bundle\ChateaClientBundle\Manager\ChannelManager',
                                'UserManager'=>'\Ant\Bundle\ChateaClientBundle\Manager\UserManager'
                                );
	
	/**
	 * get or create manager
	 */
	static public function get($apiManager, $name)
	{
		if(!array_key_exists($name,self::$managerMap)){
            throw new \InvalidArgumentException('this manager is not supported');
        }
        $className = self::$managerMap[$name];
        return self::factory($apiManager, $className);
	}

    static public function factory(ApiManager $apiManager, $manager_class_name)
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
	
}