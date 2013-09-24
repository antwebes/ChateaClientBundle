<?php

namespace Ant\Bundle\ChateaClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ant\ChateaClient\Client\ApiException;

class ChateaClientController extends Controller implements AuthenticatedController
{

    public function getApiChannels()
    {
        return $this->container->get('antwebes_channels');
    }

    protected function createHttpException($statusCode, $message, ApiException $previous = null, $code = 0)
    {
        
        $format = $this->container->getParameter('chatea_client.http_error.format');
        $message = json_decode($message, true);
        $status_text = '';
        
        //TODO: error format
        if (array_key_exists('error', $message)) {
            $status_text = array_key_exists('error', $message) && array_key_exists('error_description', $message) ? $message['error'] . ': ' . $message['error_description'] : '';
        } else {
            //TODO: API Error format
            $status_text = array_key_exists('message', $message) ? $message['message'] : $message;
        }

        $code = array_key_exists('code', $message) ? $message['code'] : $code;

        return $this->render('ChateaClientBundle:HttpException:error.' . $format . '.twig', array(
                    'status_code' => $statusCode,
                    'status_text' => $status_text,
                    'exception_message' => $previous->getMessage(),
                    'code' => $code
                        )
        );
    }

}

