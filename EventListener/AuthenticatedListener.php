<?php
namespace Ant\Bundle\ChateaClientBundle\EventListener;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Ant\Bundle\ChateaClientBundle\Controller\AuthenticatedController;

class AuthenticatedListener
{
	private $security_context;
	
	
	public function __construct(SecurityContextInterface $securityContext)
	{
		$this->security_context = $securityContext;	
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
		}
	
	}
}