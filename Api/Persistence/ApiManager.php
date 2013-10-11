<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Persistence;

use Ant\ChateaClient\Service\Client\ChateaGratisClient;

class ApiManager implements  ObjectManager
{

    /**
     * The ApiRepository instances.
     *
     * @var array
     */
    private $repositories = array();
    private $client = null;

    function __construct(ChateaGratisClient $client)
    {
        $this->client = $client;
    }

    public function getConnection()
    {
        return $this->client;
    }
    /**
     * @param string $entityName
     *
     * @return ObjectRepository|void
     */
    public function getRepository($entityName)
    {
        $entityName = ltrim($entityName, '\\');

        if (isset($this->repositories[$entityName])) {
            return $this->repositories[$entityName];
         }
        $repositoryClassName = ltrim($entityName::REPOSITORY_CLASS_NAME, '\\');

        $repository = new $repositoryClassName($this,$entityName);

        $this->repositories[$entityName] = $repository;

        return $repository;
    }

}