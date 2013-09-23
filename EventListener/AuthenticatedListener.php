<?php
namespace Ant\Bundle\ChateaClientBundle\EventListener;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Ant\Bundle\ChateaClientBundle\Controller\AuthenticatedController;
use Ant\ChateaClient\Http\IHttpClient;
use Ant\ChateaClient\OAuth2\AccessToken;

class AuthenticatedListener
{
	private $security_context;
	private $httpClient;
	
	public function __construct(SecurityContextInterface $securityContext, IHttpClient $httpClient)
	{
		$this->security_context = $securityContext;	
		$this->httpClient = $httpClient;
	}
	
	public function onKernelController(FilterControllerEvent $event)
	{
		
		$controller = $event->getController();		
		/*
		 * $controller passed can be either a class or a Closure. This is not usual in Symfony2 but it may happen.
		* If it is a class, it comes in array format
		*/
		if (!is_array($controller)) {
			return;
		}
		
		if ($controller[0] instanceof AuthenticatedController) {
			
			if (false === $this->security_context->isGranted('ROLE_API_USER')) {
				throw new AccessDeniedException('This action needs a valid user');
			}
			
			$user = $this->security_context->getToken()->getUser();
			if($user && $user->getAccesToken()){
			 $accesToken = new AccessToken($user->getAccesToken());
			 $this->httpClient->addAccesToken($accesToken);
			}
		}
	
	}
}