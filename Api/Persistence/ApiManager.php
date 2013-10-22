<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Persistence;

use Ant\ChateaClient\Client\Api;
use Ant\Bundle\ChateaClientBundle\Api\Util\Pager;

class ApiManager extends Api
{

     public $apiConnection;
     private $pager;
     /**
      * The ApiRepository instances.
      *
      * @var array
      */
     private $repositories = array();
     
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


    public function getPager()
    {
        if (null === $this->pager) {
            $this->pager = new Pager($this);
        }
        return $this->pager;
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