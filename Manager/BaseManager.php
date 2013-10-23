<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;

abstract class BaseManager
{
	private $apiManager;
	private $arrayManagers = array();
    private $managerMap = array('ChannelManager'=>'\Ant\Bundle\ChateaClientBundle\Manager\ChannelManager',
                                'UserManager'=>'\Ant\Bundle\ChateaClientBundle\Manager\UserManager'
                                );
	public function __construct(ApiManager $apiManager)
	{
		$this->apiManager = $apiManager;
	}
	
	public function getManager()
	{
		return $this->apiManager;
	}
	/**
	 * get or create manager
	 */
	public function get($name, $limit)
	{
        $className = $this->managerMap['$name'];
        return self::factory($this, $className, $limit);
	}

    public static function factory(ApiManager $apiManager, $manager_class_name, $limit= 10)
    {
        if(array_key_exists($manager_class_name,self::$arrayManagers)){
            return self::$arrayManagers[$manager_class_name];
        }
        $manager = new $manager_class_name($apiManager,$limit);
        self::$arrayManagers[$manager_class_name] = $manager;
        $model = $manager->getModel();
        $model::setManager($manager);
        return $manager;
    }
	
}