<?php
namespace Ant\Bundle\ChateaClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Ant\ChateaClient\Client\ApiException;
use Symfony\Component\HttpFoundation\Response;
use Ant\ChateaClient\Http\IHttpClient;
use Ant\ChateaClient\OAuth2\AccessToken;


class ChateaClientController extends Controller implements AuthenticatedController
{

	public function getApiChannels()
	{		
	    $tokenValue = "YjJkMzA0M2RmODc4MDk4N2FkMjllNjFmNDM5MTg4Yjk3ZGRkZjU5ODY2NWYwNGZlZGNkMmUxZGRiZjRiMTU5NA";
	    $this->container->get('chatea_client.http_client')->addAccesToken(new AccessToken($tokenValue));
		return $this->container->get('antwebes_channels');
	}
	
	protected function createHttpException ($statusCode, $message, ApiException $previous = null, $code = 0)
	{
		$headers = $previous->getHttpException()?$previous->getHttpException()->getHeaders():null;
		$format = $this->container->getParameter('chatea_client.http_error.format');		
		$message = json_decode($message,true);
		$status_text = '';
		//TODO: error format
		if(array_key_exists('error', $message)){
			$status_text = array_key_exists('error', $message) && array_key_exists('error_description', $message)? $message['error'].': '.$message['error_description']: '';
		}else{
			//TODO: API Error format
			$status_text = array_key_exists('message', $message) ? $message['message']: $message;
		}
		
		$code = array_key_exists('code', $message)?$message['code']:$code;
		
		return $this->render('ChateaClientBundle:HttpException:error.'.$format.'.twig',
				array(
						'status_code'=> $statusCode,
						'status_text' =>$status_text,
						'exception_message' => $previous->getMessage(),
						'code' => $code
				)				
		);				
	}
	
	
} 