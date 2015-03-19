<?php
namespace Ant\Bundle\ChateaClientBundle\Controller;

use Ant\Bundle\ChateaClientBundle\Api\Model\ChangeEmail;
use Ant\Bundle\ChateaClientBundle\Api\Model\Client;
use Ant\Bundle\ChateaClientBundle\Api\Model\User;
use Ant\Bundle\ChateaClientBundle\Api\Model\ChangePassword;
use Ant\Bundle\ChateaClientBundle\Form\ChangeEmailType;
use Ant\Bundle\ChateaClientBundle\Form\CreateUserType;
use Ant\Bundle\ChateaClientBundle\Form\ChangePasswordType;
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
        $apiEndpoint = $this->container->getParameter('chatea_client.api_endpoint');

        $formOptions = array(
            'language'              => $language,
            'cityLocationManager'   => $this->get('api_cities'),
            'ip'                    => $request->getClientIp(),
        );

        $user = new User();
        //$channelTypeManager = $this->get('api_channels_types');
        $userManager = $this->get('api_users');
        $form = $this->createForm(new CreateUserType(), $user, $formOptions);
        $problem = null;

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
                    $serverErrorArray = json_decode($e->getMessage(), true);

                    if(isset($serverErrorArray['error']) && $serverErrorArray['error'] == "invalid_client"){
                        $problem = 'user.register.invalid_client';
                    }else{
                        $this->addErrorsToForm($e, $form);
                    }
                }
            }
        }

        $templateVars = array(
            'form' => $form->createView(),
            'errors' => $form->getErrors(),
            'alerts' => null,
            'language' => $language,
            'api_endpoint' => $apiEndpoint,
            'problem' => $problem,
        );

        return $this->render('ChateaClientBundle:User:register.html.twig', $templateVars);
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

    /**
     * @APIUser
     *
     */
    public function userSettingsAction(Request $request)
    {
        $changePassword = new ChangePassword();
        $changeEmail = new ChangeEmail();
        $userManager = $this->get('api_users');
        $form = $this->createForm(new ChangePasswordType(), $changePassword, array('translator' => $this->get('translator')));
        $formChangeEmail = $this->createForm(new ChangeEmailType(), $changeEmail);

        if ('POST' === $request->getMethod() && $request->get('change_password')){
            $form->submit($request);
            if ($form->isValid()){
                try {
                    $userManager->changePassword($changePassword);

                    return $this->render('ChateaClientBundle:User:changePasswordSuccess.html.twig');
                }catch(\Exception $e){
                    $this->addErrorsToForm($e, $form, 'password');
                }
            }
        }

        if ('POST' === $request->getMethod() && $request->get('change_email')){
            $formChangeEmail->submit($request);
            if ($formChangeEmail->isValid()){
                try {
                    $userManager->changeEmail($changeEmail);

                    return $this->render('ChateaClientBundle:User:changeEmailSuccess.html.twig');
                }catch(\Exception $e){
                    $this->addErrorsToForm($e, $formChangeEmail, 'email');
                }
            }
        }

        return $this->render('ChateaClientBundle:User:userSettings.html.twig', array(
            'form' => $form->createView(),
            'formEmail' => $formChangeEmail->createView(),
            'errors' => $form->getErrors(),
            ));
    }

    private function getLanguageFromRequestAndSaveInSessionRequest(Request $request)
    {
        $language = $request->get('language', $this->container->getParameter('kernel.default_locale'));
        $request->setLocale($language);
        $request->getSession()->set('_locale', $language);

        return $language;
    }

    protected function addErrorsToForm($e, $form, $context = '_')
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
                $this->fillForm($errors['errors'], $form, $context);
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
    private function fillForm($errors, $form, $context)
    {
        $fieldMap = ['current_password' => ['password' => 'currentPassword', 'email' => 'password']];

        $translator = $this->get('translator');

        foreach ($errors as $field => $message) {
            if(isset($fieldMap[$field]) && isset($fieldMap[$field][$context])){
                $field = $fieldMap[$field][$context];
            }

            if ($form->has($field)){
                if (isset($message['message'])){
                    $form->get($field)->addError(new FormError($this->translateServerError($message['message'])));
                }else{
                    $this->fillForm($message, $form->get($field), $context);
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

    private function translateServerError($errorMessage)
    {
        $translator = $this->get('translator');
        $errorMapping = array(
            "This value should be the user current password." => "form.current_password_match",
        );

        if(isset($errorMapping[$errorMessage])){
            return $translator->trans($errorMapping[$errorMessage], array(), 'UserChange');
        }

        return $errorMessage;
    }
}