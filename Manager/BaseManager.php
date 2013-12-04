<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;
use Ant\Bundle\ChateaClientBundle\Api\Persistence\ObjectManager;

abstract class BaseManager implements ManagerInterface
{
	private $apiManager;
	
	public function __construct(ObjectManager $apiManager)
	{
		$this->apiManager = $apiManager;
	}
	/**
	 * get ApiManager
	 * @return ApiManager
	 */
	public function getManager()
	{
		return $this->apiManager;
	}
	/**
	 * get or create other manager
	 */
	public function get($name)
	{
		return FactoryManager::get($this->apiManager, $name);
	}
	
}