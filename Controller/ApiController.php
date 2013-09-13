<?php
namespace Ant\Bundle\ChateaClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends ChateaClientController 
{
	
	public function indexAction()
	{

		// TODO: get doc in server.
// 		$httpClient = new HttpClient(HttpClient::SERVER_ENDPOINT,'text/html');
// 		$request = $httpClient->addGet('doc/');	
// 		$body = $httpClient->send();
// 		return new Response($body);
							 
		return $this->render('ChateaClientBundle:Api:index.html.html');
	}
}