<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;

abstract class BaseManager implements ManagerInterface
{
	private $apiManager;
	
	public function __construct(ApiManager $apiManager)
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