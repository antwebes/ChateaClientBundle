<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Persistence;

use Ant\ChateaClient\Service\Client\ChateaGratisClient;

class ApiConnection
{


    private $client = null;

    function __construct(ChateaGratisClient $client)
    {
        $this->client = $client;
    }

    public function getConnection()
    {
        return $this->client;
    }
   

}