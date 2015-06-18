<?php

namespace Ant\Bundle\ChateaClientBundle\Controller;

use Ant\Bundle\ChateaClientBundle\Form\EditUserProfilePhotoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ant\Bundle\ChateaClientBundle\Security\Authentication\Annotation\APIUser;
use Ant\Bundle\ChateaClientBundle\Form\EditUserProfileType;

class ProfileController extends Controller
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
}