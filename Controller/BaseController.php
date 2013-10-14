<?php

namespace Ant\Bundle\ChateaClientBundle\Controller;

use Ant\Bundle\ChateaClientBundle\Api\Model\Channel;
use Ant\Bundle\ChateaClientBundle\Api\Model\ChannelType;
use Ant\Bundle\ChateaClientBundle\Api\Model\User;
use Ant\Bundle\ChateaClientBundle\Api\Model\Error;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;


class BaseController extends Controller 
{


    protected function getChannelRepository()
    {
        $repository = $this->getApi()->getRepository(get_class(new Channel()));
        $repository->setFileterCollection(
            $this->container->get('antwebes_api_filter_channel')
        );

        return $repository;
    }

    protected function  getUserRepository()
    {
        return $this->getApi()->getRepository(get_class(new User()));
    }
    protected function getChannelTypeRepository()
    {
        return $this->getApi()->getRepository(get_class(new ChannelType()));
    }
    protected function getApi()
    {
        if (!$this->container->has('antwebes_api')) {
            throw new \LogicException('The ChateaClientBundle is not registered in your application.');
        }

        return $this->container->get('antwebes_api');
    }

    protected function parseApiException(\Exception $exception)
    {

        ld($exception);
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

