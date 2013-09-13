<?php
namespace Ant\Bundle\ChateaClientBundle\Security\Firewall;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;

use Ant\Bundle\ChateaClientBundle\Security\Authentication\Token\OAuth2Token;
use Ant\ChateaClient\OAuth2\TokenRequest;
use Ant\ChateaClient\Client\Authentication;
use Ant\ChateaClient\Client\AuthenticationException;


class OAuthListener extends AbstractAuthenticationListener
{

	private $service_authentication;
	/**
	 * Performs authentication.
	 *
	 * @param Request $request A Request instance
	 *
	 * @return TokenInterface|Response|null The authenticated token, null if full authentication is not possible, or a Response
	 *
	 * @throws AuthenticationException if the authentication fails
	 */
	protected function attemptAuthentication(Request $request)
	{
		try{
			$this->service_authentication->authenticate();
		}catch (AuthenticationException $ex)
		{
			// To deny the authentication clear the token. This will redirect to the login page.
			// Make sure to only clear your token, not those of other authentication listeners.
			$token = $this->securityContext->getToken();
			if ($token instanceof AuToken && $this->providerKey === $token->getProviderKey()) {
			     $this->securityContext->setToken(null);
			}
			return;
			
			// Deny authentication with a '403 Forbidden' HTTP response
			//$response = new Response();
			//$response->setStatusCode(403);
			//$event->setResponse($response);			
		}
		
		$token = new OAuth2Token();
		$token->setAccessToken($this->service_authentication->getAccessToken(true));
		$token->setRefreshToken($this->service_authentication->getRefreshToken(true));
		$token->setExpiresIn($this->service_authentication->getAccessToken()->getExpiresIn());
		$token->setScope($this->service_authentication->getAccessToken()->getScope());
				
		return $this->authenticationManager->authenticate($token);
	}	
	
	public function setServiceAuthentication(Authentication $service_authentication)
	{
		$this->service_authentication = $service_authentication;
	}
}