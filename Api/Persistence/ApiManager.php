<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Persistence;

use Ant\ChateaClient\Client\Api;
use Ant\Bundle\ChateaClientBundle\Api\Util\CommandInterface;
use Guzzle\Common\Exception\InvalidArgumentException;

/**
 * Class ApiManager
 * @package Ant\Bundle\ChateaClientBundle\Api\Persistence
 */
class ApiManager extends Api implements ObjectManager
{

     public $apiConnection;
     /**
      * The ApiRepository instances.
      *
      * @var array
      */
     private static $repositories = array();
     
    public function __construct(ApiConnection $apiConnection)
    {
          parent::__construct($apiConnection->getConnection());

          $this->apiConnection = $apiConnection;

    }

     /**
      * @return ApiConnection
      */
     protected function getApiConnection()
     {
         return $this->apiConnection;
     }


    public function execute(CommandInterface $command)
    {
        $reflection = new \ReflectionMethod($this,$command->getName());
        $parametersWhoRequestFunction = $reflection->getParameters();

		$arguments = array();
       
        foreach($parametersWhoRequestFunction as $parameterWhoRequest){
            if(!array_key_exists($parameterWhoRequest->getName(),$command->getParams()) ){
            	
            	if (!$parameterWhoRequest->isDefaultValueAvailable()){
            		$messageError= 'Error in -> ChateaClienBundle/Api/Persistence/ApiManager The param "'. $parameterWhoRequest->getName() .'" without value by default, or params missing'; 
            		throw new InvalidArgumentException($messageError);
            	}
            	array_push($arguments,$parameterWhoRequest->getDefaultValue());
            }else{
            	array_push($arguments,$command->getParam($parameterWhoRequest->getName()));
            }
        };
        $responseBody = call_user_func_array(array($this,$command->getName()),$arguments);
        return $responseBody;
    }
    /**
     * @param string $entityName
     *
     * @return ObjectRepository|void
     */
    public function getManager($entityName)
    {
    	$entityName = ltrim($entityName, '\\');

    	if (isset($this->repositories[$entityName])) {
    		return $this->repositories[$entityName];
    	}
    	$repositoryClassName = ltrim($entityName::MANAGER_CLASS_NAME, '\\');

    	$repository = new $repositoryClassName($this,$entityName);

    	$this->repositories[$entityName] = $repository;

    	return $repository;
    }
}