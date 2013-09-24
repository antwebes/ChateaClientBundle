<?php
namespace Ant\Bundle\ChateaClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ant\ChateaClient\Client\ApiException;

use Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener;
class ChannelController extends ChateaClientController
{

    public function showAllAction(Request $request)
    {        
        try {
            $channels = $this->getApiChannels()->show();
        } catch (ApiException $ex) {
            return $this->createHttpException($ex->getStatusCode(), $ex->getServerError(), $ex, $ex->getStatusCode());
        }
        
        return $this->render('ChateaClientBundle:Channel:index.html.twig',
            array(
                'channels'=> $channels
            )
        );		    

	}	
}