<?php
namespace Ant\Bundle\ChateaClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ChateaClientController extends Controller
{

	public function getChateaApi()
	{		
		return $this->container->get('antwebes_chateaclient.api');
	}
	
	protected function createHttpException ($statusCode, $message = null, \Exception $previous = null, array $headers = array(), $code = 0)
	{
		return new HttpException($statusCode,$message,$previous,$headers,$code);
	}
	
	
} 