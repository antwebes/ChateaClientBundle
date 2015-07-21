<?php
namespace Ant\Bundle\ChateaClientBundle\Controller;

use Ant\Bundle\ChateaClientBundle\Api\Model\Channel;
use Ant\Bundle\ChateaClientBundle\Form\CreateChannelType;
use Ant\Bundle\ChateaClientBundle\Security\Authentication\Annotation\APIUser;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
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
        $language = $this->container->getParameter('kernel.default_locale');
        $form = $this->createForm(new CreateChannelType(), $channel, ['channelTypeManager' => $channelTypeManager, 'language' => $language]);

        if ('POST' === $request->getMethod()){
            $form->submit($request);
            if ($form->isValid()){
                try {
                    $channelManager->save($channel);
                    return $this->render('ChateaClientBundle:Channel:registerSuccess.html.twig');
                }catch(\Exception $e){
                    $this->addErrorsToForm($e, $form);
                }
            }
        }

        return $this->render('ChateaClientBundle:Channel:register.html.twig', [
            'form' => $form->createView(),
            'from_has_error' => count($form->getErrors()) > 0,
        ]);
    }

    protected function addErrorsToForm($e, $form)
    {
        $translator = $this->get('translator');

        if ($this->isJson($e->getMessage())){
            $serverError = json_decode($e->getMessage());
            $serverErrorArray = json_decode($e->getMessage(), true);

            if(!isset($serverErrorArray['errors'])){
                return true;
            }

            if(is_object($serverError->errors)){
                $errors = json_decode($e->getMessage(), true);
                $this->fillForm($errors['errors'], $form);
            }else{
                if($e->getCode() == 403){
                    $serverError->errors .= '. You are not have validate your account.';
                }
                $form->addError(new FormError($this->translateServerMessage($serverError->errors)));
            }
        } else {
            //llega un string de error proveniente de la libreria
            throw new \Exception($e);
        }
    }

    /**
     *
     * @param array $errors errors of the form
     * @param Form $form form where we go to include the errors
     */
    private function fillForm($errors, $form)
    {
        $translator = $this->get('translator');

        foreach ($errors as $field => $message) {

            if ($form->has($field)){
                if (isset($message['message'])){
                    $form->get($field)->addError(new FormError($translator->trans('%%error%%', array('%%error%%' => $this->translateServerMessage($message['message'])))));
                }else{
                    //in this case the message is a form field within another field.
                    $this->fillForm($message, $form->get($field));
                }
            }else{
                if(!isset($message['message']) && preg_match('/slug/i', $message)){
                    continue;
                }

                //receive an error from the library
                //the message can to be a string :"This form should not contain extra fields." For this reason I include this if
                $messageString = (isset($message['message']) ? $message['message']: $message);
                $messageString = $this->translateServerMessage($messageString);
                $form->addError(new FormError($translator->trans('%%error%%', array('%%error%%' => $field . '->'.$messageString))));
            }
        }
    }

    private function isJson($string)
    {
        return (is_string($string) && (
                is_object(json_decode($string)) || is_array(json_decode($string))
            )
        );
    }

    private function translateServerMessage($message)
    {
        $errorMap = array(
            'This user has no permission for this action. You are not have validate your account.' => 'form.user_no_permission.user_not_enabled',
            'This user has no permission for this action' => 'form.user_no_permission',
            'The user should login in irc service at least once' => 'form.login_once',
            'The irc channel value is not valid. view RFC-1459' => 'form.rfc_1459',
            'The channel name is already used.' => 'form.channel_name_already_used',
        );

        $translator = $this->get('translator');

        if(!isset($errorMap[$message])){
            return $message;
        }

        return $translator->trans($errorMap[$message], array(), 'ChannelRegistration');
    }
}