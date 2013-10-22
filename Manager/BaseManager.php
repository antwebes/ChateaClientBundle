<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;

abstract class BaseManager
{
	private $apiManager;
	
	public function __construct(ApiManager $apiManager)
	{
		$this->apiManager = $apiManager;
	}
	
	public function getManager()
	{
		return $this->apiManager;
	}
}