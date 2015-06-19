<?php

namespace Ant\Bundle\ChateaClientBundle\Controller;

use Ant\Bundle\ChateaClientBundle\Form\EditUserProfilePhotoType;
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
    	$userOnline = $this->getUser();

    	/** @var \Ant\Bundle\ChateaClientBundle\Manager\UserManager $userManager */
    	$userManager = $this->container->get('api_users');
    	
    	$user = $userManager->findById($userOnline->getId());

    	if (is_null($user->getProfile())){
    		return $this->redirect($this->generateUrl('chatea_user_profile', array('userId'=>$user->getId())));
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
    
}