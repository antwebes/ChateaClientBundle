<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiRepository;

interface BaseModel
{

    static function  setRepository(ApiRepository $repository);
    static function  getRepository();
    public function __toString();
}