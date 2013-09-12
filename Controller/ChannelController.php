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
			ldd($ex);
			//throw $this->createNotFoundException('ChannelController::indexAction() ERROR : Not found section');
		}
		return new Response("Holas dende channel controler");
	}
	public function createNotFoundException($message = 'Not Found', \Exception $previous = null)
	{
		return new NotFoundHttpException($message, $previous);
	}	
}