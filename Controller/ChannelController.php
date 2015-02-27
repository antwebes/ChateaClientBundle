<?php
namespace Ant\Bundle\ChateaClientBundle\Controller;

use Ant\Bundle\ChateaClientBundle\Api\Model\Channel;
use Ant\Bundle\ChateaClientBundle\Form\CreateChannelType;
use Ant\Bundle\ChateaClientBundle\Security\Authentication\Annotation\APIUser;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ChannelController extends Controller
{
    /**
     * @APIUser
     *
     */
    public function registerAction(Request $request)
    {
        $channel = new Channel();
        $channelTypeManager = $this->get('api_channels_types');
        $channelManager = $this->get('api_channels');
        $form = $this->createForm(new CreateChannelType(), $channel, ['channelTypeManager' => $channelTypeManager]);

        if ('POST' === $request->getMethod()) {
            $form->submit($request);
            if ($form->isValid()) {
                try {
                    $channelManager->save($channel);
                    return $this->render('ChateaClientBundle:Channel:registerSuccess.html.twig');
                }catch(\Exception $e){

                }
            }
        }

        return $this->render('ChateaClientBundle:Channel:register.html.twig', [
            'form' => $form->createView(),
            'from_has_error' => count($form->getErrors()) > 0,
        ]);
    }
}