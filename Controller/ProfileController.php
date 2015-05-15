<?php

namespace Ant\Bundle\ChateaClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ant\Bundle\ChateaClientBundle\Security\Authentication\Annotation\APIUser;
use Ant\Bundle\ChateaClientBundle\Form\CreateUserProfileType;

class ProfileController extends Controller
{
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
    		return $this->redirect($this->generateUrl('chatea_user_profile'));
    	}else{
    		$userProfile = $user->getProfile();
    		
    		//of api I get a date time... so I must convert to date time to render in form CreateUserProfileType
    		$timestamp = strtotime($userProfile->getBirthday());
    		$date = new \DateTime();
    		$date->setTimestamp($timestamp);
    		$userProfile->setBirthday($date);
    	}
    	
    	$form = $this->createForm(new CreateUserProfileType(),$userProfile);
    	
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
    
    private function getLanguageFromRequestAndSaveInSessionRequest(Request $request)
    {
    	$language = $request->get('language', $this->container->getParameter('kernel.default_locale'));
    	$request->setLocale($language);
    	$request->getSession()->set('_locale', $language);
    
    	return $language;
    }
}