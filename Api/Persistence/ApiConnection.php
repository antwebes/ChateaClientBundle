<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Persistence;

use Ant\ChateaClient\Service\Client\ChateaGratisAppClient;

class ApiConnection
{


    private $client = null;

    function __construct(ChateaGratisAppClient $client)
    {
        $this->client = $client;
    }

    public function getConnection()
    {
        return $this->client;
    }
   

}