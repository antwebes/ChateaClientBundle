<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;
use Ant\Bundle\ChateaClientBundle\Api\Persistence\ObjectManager;
use Ant\ChateaClient\Client\ApiException;

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

    /**
     * Executes a command and returns the result. If ApiException happens and has status code 404 null is returned else the exception
     * @param $command Service to execute
     * @param $params parameters to pass to the service
     * @return array
     * @throws ApiException
     * @throws \Exception
     */
    protected function executeAndHandleApiException($command, $params)
    {
        try{
            $manager = $this->getManager();

            $data = call_user_func_array(array($manager, $command), $params);
        }catch(ApiException $e){
            if($e->getCode() == 404){
                return null;
            }

            throw $e;
        }

        return $data;
    }

    /**
     * Executes a command and hydrates the result. If ApiException happens and has status code 404 null is returned else the exception
     * @param $command Service to execute
     * @param $params parameters to pass to the service
     * @return Object
     * @throws ApiException
     * @throws \Exception
     */
    protected function executeAndHydrateOrHandleApiException($command, $params)
    {
        $data = $this->executeAndHandleApiException($command, $params);

        if($data !== null){
            return $this->hydrate($data);
        }

        return null;
    }
}