<?php

namespace Ant\Bundle\ChateaClientBundle\Controller;

use Ant\ChateaClient\Client\ApiException;

class ChannelController extends ChateaClientController
{

    public function showAllAction()
    {
        try {
            $channels = $this->getApiChannels()->show();
        } catch (ApiException $ex) {
            return $this->createHttpException($ex->getStatusCode(), $ex->getServerError(), $ex, $ex->getStatusCode());
        }

        return $this->render('ChateaClientBundle:Channel:index.html.twig', array(
                    'channels' => $channels
                        )
        );
    }

}