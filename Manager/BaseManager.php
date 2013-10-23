<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;

abstract class BaseManager
{
	private $apiManager;
	private $arrayManagers = array();
	
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
		if (array_key_exists($name, $this->arrayManagers)) {
			$this->arrayManagers[$name];
		}
		$namespace = '\Ant\Bundle\ChateaClientBundle\Manager\\'.$name;
		$manager = new $namespace($this->apiManager, 25);
		$this->arrayManagers[$name] = $manager;
		
		$model = $manager->getModel();
		$model::setManager($manager);
		return $manager;
	}
	
}