<?php
namespace Ant\Bundle\ChateaClientBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ant\ChateaClient\Client\ApiException;

class ChannelController extends ChateaClientController 
{
	public function showAllAction(Request $request)
	{
		
		try {
		 $channels = $this->getChateaApi()->showChannels();
		}catch (ApiException $ex){
			return $this->createHttpException($ex->getStatusCode(),$ex->getServerError(),$ex,$ex->getStatusCode());
		}
		
		return new Response("Holas dende channel controler");
	}	
}