<?php

namespace Ant\Bundle\ChateaClientBundle\Controller;

use Ant\Bundle\ChateaClientBundle\Model\Error;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Ant\ChateaClient\Client\ApiException;

class BaseController extends Controller 
{


    protected function getChannelRepository()
    {

        $className = 'Ant\\Bundle\\ChateaClientBundle\\Model\\Channel';
        return $this->getApi()->getRepository($className);
    }

    protected function  getUserRepository()
    {
        $className = 'Ant\\Bundle\\ChateaClientBundle\\Model\\User';

        return $this->getApi()->getRepository($className);
    }
    protected function getChannelTypeRepository()
    {
        $className = 'Ant\\Bundle\\ChateaClientBundle\\Model\\ChannelType';
        return $this->getApi()->getRepository($className);
    }
    protected function getApi()
    {
        if (!$this->container->has('antwebes_api')) {
            throw new \LogicException('The ChateaClientBundle is not registered in your application.');
        }

        return $this->container->get('antwebes_api');
    }


    protected function parseApiException(ApiException $exception)
    {

        $json_decode = json_decode($exception->getServerError(), true);

        if(!$json_decode)
        {
            throw $exception;
        }
        if(is_string($json_decode['errors']))
        {
            return new Error($json_decode['errors'],$json_decode['code']);
        }elseif (is_array($json_decode['errors']))
        {
            $message = '';
            foreach($json_decode['errors'] as $field=>$msg){

                $message.=  $field.': ' . $msg['message']."\n";

            }
            return new Error($message,$json_decode['code'],$json_decode['errors'] );
        }else
        {
            throw new \Exception("Not soport formar");
        }

    }

    protected  function sendError($message, $return_route = '')
    {
        $response = $this->forward('ChateaClientBundle:Default:error',array(
                'message'  => $message,
                'return' => $this->generateUrl($return_route),
            ));

        return $response;

    }
}

