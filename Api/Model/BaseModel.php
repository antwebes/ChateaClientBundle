<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

interface BaseModel
{
    static function  setManager($manager);
    static function  getManager();
    public function __toString();
}