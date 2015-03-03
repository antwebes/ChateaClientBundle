<?php
namespace Ant\Bundle\ChateaClientBundle\Controller;

use Ant\Bundle\ChateaClientBundle\Api\Model\Client;
use Ant\Bundle\ChateaClientBundle\Api\Model\User;
use Ant\Bundle\ChateaClientBundle\Form\CreateUserType;
use Ant\Bundle\ChateaSecureBundle\Security\User\User as SecureUser;
use Ant\Bundle\ChateaClientBundle\Security\Authentication\Annotation\APIUser;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserController extends Controller
{
    public function registerAction(Request $request)
    {
        $language = $this->getLanguageFromRequestAndSaveInSessionRequest($request);

        $formOptions = array(
            'language'              => $language,
            'cityLocationManager'   => $this->get('api_cities'),
            'ip'                    => $request->getClientIp(),
        );

        $user = new User();
        //$channelTypeManager = $this->get('api_channels_types');
        $userManager = $this->get('api_users');
        $form = $this->createForm(new CreateUserType(), $user, $formOptions);

        if ('POST' === $request->getMethod()) {
            $form->submit($request);
            if ($form->isValid()) {
                try {
                    $client = new Client();
                    $client->setId($this->container->getParameter('chatea_client.app_id'));

                    $user->setClient($client);
                    $userManager->save($user);

                    $this->authenticateUser($user);

                    return $this->render('ChateaClientBundle:User:registerSuccess.html.twig');
                }catch(\Exception $e){
                    $this->addErrorsToForm($e, $form);
                }
            }
        }

        return $this->render('ChateaClientBundle:User:register.html.twig', array(
            'form' => $form->createView(),
            'errors' => $form->getErrors(),
            'alerts' => null,
            'language' => $language,
        ));
    }

    public function confirmEmailAction()
    {
        if ($this->getUser() != null && !$this->getUser()->isValidated()){
            $accessToken = $this->getUser()->getAccessToken();
            $apiEndpoint = $this->container->getParameter('chatea_client.api_endpoint');

            $response = $this->render('ChateaClientBundle:User:confirmEmail.html.twig',array('userAccessToken' => $accessToken, 'api_endpoint' => $apiEndpoint));

            return $response;
        }

        return $this->redirect($this->generateUrl('homepage'));
    }

    public function confirmedAction(Request $request)
    {
        $id             = $request->get('id');
        $accessToken    = $request->get('access_token');
        $refreshToken   = $request->get('refresh_token');
        $expiresIn      = $request->get('expires_in');
        $scope          = $request->get('scope');
        $scope          = is_array($scope)?$scope:array($scope);
        $tokenType      = $request->get('token_type');
        $username       = $request->get('username');
        $roles          = explode(",", $request->get('roles'));

        if($username == ''){
            throw new BadRequestHttpException();
        }

        $newSecureUser =  new SecureUser($id,$username,$accessToken,$refreshToken, true,$tokenType,$expiresIn,$scope,$roles);
        $authenticatedToken = new UsernamePasswordToken($newSecureUser, null, 'secured_area', $newSecureUser->getRoles());
        //put new token in session
        $this->get('session')->set('_security_main',serialize($authenticatedToken));

        $redirectResponse = $this->redirect($this->generateUrl('chatea_confirmed_success'));
        /*$remberMeService = $this->get('api.rememberme.service');

        $remberMeService->regenerateRememberMeTokenIfPresent($request, $redirectResponse, $authenticatedToken);*/
        $this->get('security.context')->setToken($authenticatedToken);

        return $redirectResponse;
    }

    public function confirmedSuccessAction()
    {
        if($this->getUser() == null){
            throw new AccessDeniedHttpException();
        }

        $response =  $this->render('ChateaClientBundle:User:confirmedSuccess.html.twig',array('user'=>$this->getUser()));

        return $response;
    }

    private function getLanguageFromRequestAndSaveInSessionRequest(Request $request)
    {
        $language = $request->get('language','en');
        $request->setLocale($language);
        $request->getSession()->set('_locale', $language);

        return $language;
    }

    protected function addErrorsToForm($e, $form)
    {
        $translator = $this->get('translator');

        if ($this->isJson($e->getMessage())){
            $serverError = json_decode($e->getMessage());
            ldd($serverError);
            if(is_object($serverError->errors)){
                $errors = json_decode($e->getMessage(), true);
                $this->fillForm($errors['errors'], $form);
            }else{
                $form->addError(new FormError($translator->trans('%%error%%', array('%%error%%' => $serverError->errors))));
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
                    $form->get($field)->addError(new FormError($translator->trans('%%error%%', array('%%error%%' => $message['message']))));
                }else{
                    //in this case the message is a form field within another field.
                    $this->fillForm($message, $form->get($field));
                }
            }else{
                //receive an error from the library
                //the message can to be a string :"This form should not contain extra fields." For this reason I include this if
                $messageString = (isset($message['message']) ? $message['message']: $message);
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

    private function authenticateUser($user)
    {
        try{
            $token = new UsernamePasswordToken($user->getUsername(), $user->getPlainPassword(), 'secured_area');
            $token = $this->get('security.authentication.manager')->authenticate($token);
            $this->get('security.context')->setToken($token);
        }catch(\Exception $e){

        }
    }
}