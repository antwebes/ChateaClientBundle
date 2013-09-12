<?php
namespace Ant\Bundle\ChateaClientBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;


class AuthorizeController extends ContainerAware 
{
	public function connectAction(Request $request)
	{
													 
		$serviceId = $this->container->getParameter('antwebes_chateaclient.auth_client.default');
		$authService = $this->container->get($serviceId);

		$accessToken = $authService->getAccessToken();
		$refreshToken = $authService->getRefreshToken();
		
		return new Response("accesToken => ".$accessToken);
	}
}