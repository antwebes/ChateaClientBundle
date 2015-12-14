<?php
namespace Ant\Bundle\ChateaClientBundle\Controller;

use Ant\Bundle\ChateaClientBundle\Api\Model\ChangeEmail;
use Ant\Bundle\ChateaClientBundle\Api\Model\Client;
use Ant\Bundle\ChateaClientBundle\Api\Model\User;
use Ant\Bundle\ChateaClientBundle\Api\Model\ChangePassword;
use Ant\Bundle\ChateaClientBundle\Api\Model\UserProfile;
use Ant\Bundle\ChateaClientBundle\Form\ChangeEmailType;
use Ant\Bundle\ChateaClientBundle\Form\CreateUserProfileType;
use Ant\Bundle\ChateaClientBundle\Form\CreateUserType;
use Ant\Bundle\ChateaClientBundle\Form\ChangePasswordType;
use Ant\Bundle\ChateaSecureBundle\Security\User\User as SecureUser;
use Ant\Bundle\ChateaClientBundle\Security\Authentication\Annotation\APIUser;
use Ant\Bundle\ChateaClientBundle\Event\UserEvent;
use Ant\Bundle\ChateaClientBundle\Event\ChateaClientEvents;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends BaseController
{
    public function registerAction(Request $request)
    {
        $language = $this->getLanguageFromRequestAndSaveInSessionRequest($request);
        $apiEndpoint = $this->container->getParameter('chatea_client.api_endpoint');
        $countriesPath = __DIR__ . '/../Resources/config/countries.json';

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($request->getBaseUrl());
        }

            $formOptions = array(
            'language'              => $language,
            'cityLocationManager'   => $this->get('api_cities'),
            'ip'                    => $request->getClientIp(),
        );

        $user = new User();
        /** @var \Ant\Bundle\ChateaClientBundle\Manager\UserManager $userManager */
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

                    // create the FilterOrderEvent and dispatch it
                    $event = new UserEvent($user, $request);
                    $this->container->get('event_dispatcher')->dispatch(ChateaClientEvents::USER_REGISTER_SUCCESS, $event);

                    $userManager->save($user);
                    $birthday = $form->get('birthday')->getData()->format('Y-m-d');
                    $request->getSession()->set('user_'.$user->getId().'.birthday',$birthday);

                    $this->authenticateUser($user);

                    if($this->container->getParameter('chatea_client.register_with_profile') == true){
                        return $this->redirect($this->generateUrl('chatea_user_profile'));
                    }
                    return $this->render('ChateaClientBundle:User:registerSuccess.html.twig', array('user' => $user));
                }catch(\Exception $e){
                    $serverErrorArray = json_decode($e->getMessage(), true);

                    if(isset($serverErrorArray['error']) && $serverErrorArray['error'] == "invalid_client"){
                        $problem = 'user.register.invalid_client';
                    }else{
                        $this->addErrorsToForm($e, $form, 'UserRegistration');
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
            'countries' => $this->loadCountries($countriesPath)
        );

        return $this->render('ChateaClientBundle:User:register.html.twig', $templateVars);
    }

    /**
     * @param $userId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registrationUserSuccessAction($userId)
    {
        /** @var \Ant\Bundle\ChateaClientBundle\Manager\UserManager $userManager */
        $userManager = $this->container->get('api_users');

        $user = $userManager->findById($userId);
        if($user != null){
            if($user->isEnabled()){
                return $this->redirect($this->generateUrl('chatea_client_update_profile_index'));
            }

            return $this->render('ChateaClientBundle:User:registerSuccess.html.twig', array('user' => $user));
        }else{
            throw $this->createNotFoundException();
        }

    }

    /**
     * @APIUser
     */
    public function registerProfileAction(Request $request)
    {
        /** @var \Ant\Bundle\ChateaClientBundle\Manager\UserManager $userManager */
        $userManager = $this->container->get('api_users');
        $api = $this->container->get('antwebes_chateaclient_manager');

        $userId = $this->getUser()->getId();

        $user = $userManager->findById($userId);

        $birthday = $request->getSession()->get('user_'.$user->getId().'.birthday');

        if (!is_null($user->getProfile())){
        	//HACER UN REDIRECT A LA URL DE MODIFICAR PERFIL
        	$userProfile = $user->getProfile();
        }else{
        	$userProfile = new UserProfile();
        }
        
        $form = $this->createForm(new CreateUserProfileType(),$userProfile,array('birthday'=>$birthday));

        $language = $this->getLanguageFromRequestAndSaveInSessionRequest($request);
        $problem = null;

        if ('POST' === $request->getMethod()) {
            $form->submit($request);
            if ($form->isValid()) {
                try {
                    $files = $request->files->all();
                    /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $image */
//                    $image = $files[$form->getName()]['image'];
//                    $image->move($image->getPath(),$image->getFilename());
//                    $filename = $image->getPath() . '/'.$image->getFilename();
                    $user->setProfile($userProfile);
                    $userManager->addUserProfile($user);
                    //$api->addPhoto($user->getId(), $filename, ' ');
                    return $this->render('ChateaClientBundle:User:registerSuccess.html.twig', array('user' => $user));

                }catch(\Exception $e){
                    $serverErrorArray = json_decode($e->getMessage(), true);

                    if(isset($serverErrorArray['error']) && $serverErrorArray['error'] == "invalid_client"){
                        $problem = 'user.register.invalid_client';
                    }else{
                        $this->addErrorsToForm($e, $form, 'UserRegistration');
                    }
                }
            }
        }
        return $this->render('ChateaClientBundle:User:register_profile.html.twig', array(
            'user' => $user,
            'language' => $language,
            'problem' => $problem,
            'form' => $form->createView(),
            'alerts' => null,
            'errors' => $form->getErrors(),
            'access_token' => $this->container->get('security.context')->getToken()->getUser()->getAccessToken(),
            'api_endpoint' => $this->container->getParameter('api_endpoint')
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

    /**
     * @deprecated
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmedAction(Request $request)
    {
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')){
            throw new AccessDeniedHttpException();
        }

        $response = $this->render('ChateaClientBundle:User:confirmedSuccess.html.twig');

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

    /**
     * @APIUser
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function wellcomeAction(Request $request)
    {
        $userManager = $this->get('api_users');
        $cityManager = $this->get('api_cities');
        $visitsLimit = $this->container->getParameter('chatea_client.visits_limit');
        $wellcomeChannelsLimit = $this->container->getParameter('chatea_client.wellcome_channels_limit');
        $wellcomeCountryUsersLimit = $this->container->getParameter('chatea_client.wellcome_country_users_limit');
        $userMe = $userManager->findMeUser();
        $visitors = $userManager->getUserVisits($userMe, $visitsLimit);

        if($userMe->getCity() != null){
            $city = $cityManager->findById($userMe->getCity()->getId());
            $country = $city->getCountryObject();
            $filters = array('country' => $country->getCode());
            $usersCountry = $userManager->findAll(1, $filters, $wellcomeCountryUsersLimit, array('hasProfilePhoto' => 'desc', 'lastLogin' => 'desc'));
        }else{
            $usersCountry = array();
            $country = null;
        }

        return $this->render('ChateaClientBundle:User:wellcome.html.twig', array(
            'userMe' => $userMe,
            'visitors' => $visitors,
            'wellcomeChannelsLimit' => $wellcomeChannelsLimit,
            'usersCountry' => $usersCountry,
            'country' => $country
        ));
    }

    private function getLanguageFromRequestAndSaveInSessionRequest(Request $request)
    {
        $language = $request->get('language', $this->container->getParameter('kernel.default_locale'));
        $request->setLocale($language);
        $request->getSession()->set('_locale', $language);

        return $language;
    }

    /**
     *
     * @param array $errors errors of the form
     * @param Form $form form where we go to include the errors
     */
    protected function fillForm($errors, $form, $context)
    {
        $fieldMap = ['current_password' => ['password' => 'currentPassword', 'email' => 'password']];

        $translator = $this->get('translator');

        foreach ($errors as $field => $message) {
            if(isset($fieldMap[$field]) && isset($fieldMap[$field][$context])){
                $field = $fieldMap[$field][$context];
            }

            if(in_array($context, array('_', 'password', 'email'))){
                $context = 'UserChange';
            }

            if ($form->has($field)){
                if (isset($message['message'])){
                    $form->get($field)->addError(new FormError($this->translateServerError($message['message'], $context)));
                }else{
                    $this->fillForm($message, $form->get($field), $context);
                }
            }else{
                //receive an error from the library
                //the message can to be a string :"This form should not contain extra fields." For this reason I include this if
                $messageString = (isset($message['message']) ? $message['message']: $message);
                $form->addError(new FormError($translator->trans('%%error%%', array('%%error%%' => $field . '->'.$messageString), $context)));
            }
        }
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

    private function translateServerError($errorMessage, $translationContext = 'UserChange')
    {
        $translator = $this->get('translator');
        $errorMapping = array(
            "This value should be the user current password." => "form.current_password_match",
            "This value is already used." => "form.username_already_used",
            "The email is already used" => "form.mail_is_aviable",
        );

        if(isset($errorMapping[$errorMessage])){
            return $translator->trans($errorMapping[$errorMessage], array(), $translationContext);
        }else if(preg_match("/The email '(.+)' is not a valid email/", $errorMessage, $matches)){;
            return $translator->trans('form.email_not_valid_server', array('%email%' => $matches[1]), $translationContext);
        }

        return $errorMessage;
    }

    private function loadCountries($countriesPath)
    {
        $content = json_decode(file_get_contents($countriesPath), true);

        $builder = function($entry){
            $country = $entry['name'];

            unset($entry['name']);

            $hasCities = $entry['has_cities'] ? 'true' : 'false';
            $value = '{"country_code":"'.$entry['country_code'].'","has_cities":'.$hasCities.',"city_default":'.$entry['city_default'].'}';

            return array('value' => $value, 'name' => $country);
        };

        return array_map($builder, $content);
        return implode('', array_map($builder, $content));
    }
}