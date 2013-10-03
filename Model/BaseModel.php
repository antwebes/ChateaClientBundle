<?php

namespace Ant\Bundle\ChateaClientBundle\Model;

use Ant\Bundle\ChateaClientBundle\Api\Repository\ApiRepository;

interface BaseModel
{

    static function  setRepository(ApiRepository $repository);
    static function  getRepository();
    public function __toString();
}