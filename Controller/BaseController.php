<?php

namespace Ant\Bundle\ChateaClientBundle\Controller;

use Ant\Bundle\ChateaClientBundle\Api\Model\Error;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class BaseController extends Controller 
{

    protected  function getChannelRepository()
    {
        return $this->get('api_channels');
    }


    protected function getChannelTypeRepository()
    {
        return $this->get('api_channels_types');
    }

    protected function  getUserRepository()
    {
        return $this->get('api_users');
    }
}

