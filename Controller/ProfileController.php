<?php

namespace Ant\Bundle\ChateaClientBundle\Controller;

use Ant\Bundle\ChateaClientBundle\Form\EditCityType;
use Ant\Bundle\ChateaClientBundle\Form\EditUserProfilePhotoType;
use Ant\Bundle\ChateaClientBundle\Form\Model\EditCity;
use Symfony\Component\HttpFoundation\Request;

use Ant\Bundle\ChateaClientBundle\Security\Authentication\Annotation\APIUser;
use Ant\Bundle\ChateaClientBundle\Form\EditUserProfileType;
use Symfony\Component\Form\FormError;

class ProfileController extends BaseController
{
	public function updateIndexAction(Request $request)
	{
		return $this->render('ChateaClientBundle:User:edit_profile_index.html.twig');
	}

	public function updatePhotoAction(Request $request)
	{
		$form = $this->createForm(new EditUserProfilePhotoType());
		$userOnline = $this->getUser();

		/** @var \Ant\Bundle\ChateaClientBundle\Manager\UserManager $userManager */
		$userManager = $this->container->get('api_users');

		$user = $userManager->findById($userOnline->getId());

		return $this->render('ChateaClientBundle:User:edit_profile_photo.html.twig', array(
			'form' => $form->createView(),
			'user' => $user,
			'access_token' => $userOnline->getAccessToken(),
			'api_endpoint' => $this->container->getParameter('api_endpoint')
		));
	}

	/**
	 * @APIUser
	 */
    public function updateAction(Request $request)
    {
        $user = $this->getOnlineUserFromApi();
        $userManager = $this->container->get('api_users');

    	if (is_null($user->getProfile())){
    		return $this->redirect($this->generateUrl('chatea_user_profile'));
    	}else{
    		$userProfile = $user->getProfile();

			//of api I get a date time... so I must convert to date time to render in form CreateUserProfileType
			$timestamp = strtotime($userProfile->getBirthday());
			$date = new \DateTime();
			$date->setTimestamp($timestamp);
			$userProfile->setBirthday($date);
    	}
    	
    	$form = $this->createForm(new EditUserProfileType(),$userProfile);
    	
    	$language = $this->getLanguageFromRequestAndSaveInSessionRequest($request);
    	$problem = null;
		$success = null;

    	if ('POST' === $request->getMethod()) {
    		$form->submit($request);
    		if ($form->isValid()) {
    			try {
    				$user->setProfile($userProfile);
    				$userManager->updateProfile($user);
					$success = true;
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

    	return $this->render('ChateaClientBundle:User:edit_profile.html.twig', array(
    			'user' => $user,
    			'language' => $language,
    			'problem' => $problem,
    			'form' => $form->createView(),
    			'alerts' => null,
				'success' => $success,
    			'errors' => $form->getErrors(),
    			'access_token' => $this->container->get('security.context')->getToken()->getUser()->getAccessToken(),
    			'api_endpoint' => $this->container->getParameter('api_endpoint')
    	));
    	 
    }

    /**
     * @APIUser
     */
    public function editCityAction(Request $request)
    {
        $userOnline = $this->getOnlineUserFromApi();
        $editCity = new EditCity();
        $success = null;
        $editCity->setCity($userOnline->getCity());

        $countriesPath = __DIR__ . '/../Resources/config/countries.json';

        $formOptions = array(
            'cityLocationManager'   => $this->get('api_cities'),
        );

        $form = $this->createForm(new EditCityType(), $editCity, $formOptions);

        if('POST' === $request->getMethod()){
            $form->submit($request);
            if ($form->isValid()) {
                try{
                    $this->get('api_users')->updateUserCity($userOnline, $editCity->getCity());
                    $success = true;
                }catch(\Exception $e){

                }
            }
        }

        return $this->render('ChateaClientBundle:User:edit_city.html.twig', array(
            'form' => $form->createView(),
            'user' => $this->getUser(),
            'city' => $editCity->getCity(),
            'countries' => $this->loadCountries($countriesPath),
            'access_token' => $this->getUser()->getAccessToken(),
            'api_endpoint' => $this->container->getParameter('api_endpoint'),
            'success' => $success
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
    
    private function translateServerMessage($message)
    {
    	$errorMap = array(
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

    /**
     * @return array
     */
    protected function getOnlineUserFromApi()
    {
        $userOnline = $this->getUser();

        /** @var \Ant\Bundle\ChateaClientBundle\Manager\UserManager $userManager */
        $userManager = $this->container->get('api_users');

        $user = $userManager->findById($userOnline->getId());

        return $user;
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
    }
}