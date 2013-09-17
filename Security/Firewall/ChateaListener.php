<?php

namespace Ant\Bundle\ChateaClientBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Ant\Bundle\ChateaClientBundle\Security\Authentication\Token\OAuthToken;

class ChateaListener implements ListenerInterface {
	
	/**
	 * Used to define the name of the OAuth access token parameter
	 * (POST & GET).
	 * This is for the "bearer" token type.
	 * Other token types may use different methods and names.
	 *
	 * IETF Draft section 2 specifies that it should be called "access_token"
	 *
	 * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-bearer-06#section-2.2
	 * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-bearer-06#section-2.3
	 */
	const TOKEN_PARAM_NAME = 'access_token';
	
	/**
	 * When using the bearer token type, there is a specifc Authorization header
	 * required: "Bearer"
	 *
	 * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-bearer-04#section-2.1
	 */
	const TOKEN_BEARER_HEADER_NAME = 'Bearer';
	protected $securityContext;
	protected $authenticationManager;
	
	public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager) 
	{
		$this->securityContext = $securityContext;
		$this->authenticationManager = $authenticationManager;
	}
	public function handle(GetResponseEvent $event) 
	{
		$request = $event->getRequest ();
		
		// Get access token
		$access_token = $this->getBearerToken ( $request );
		if(!$access_token)
		{
			ld("ChateaListener::handle->access_token->null");
			return;
		}
		$token = new OAuthToken ();
		$token->setToken ( $access_token );
		
		try {
			$authToken = $this->authenticationManager->authenticate($token);
			$this->securityContext->setToken($authToken);
			
			ld("ChateaListener::handle->out->ok");			
			return;
					
		} catch ( AuthenticationException $failed ) {
			ld();
			// To deny the authentication clear the token. This will redirect to the login page.
			// Make sure to only clear your token, not those of other authentication listeners.
			$token = $this->securityContext->getToken ();
			if ($token instanceof OAuthToken && $this->providerKey === $token->getProviderKey ()) {
				$this->securityContext->setToken ( null );
			}
			return;
		}
		
		// By default deny authorization
		$response = new Response ( NULL, 403 );
		$event->setResponse ( $response );
	}
	
	/**
	 *
	 *
	 * As per the Bearer spec (draft 8, section 2) - there are three ways for a client
	 * to specify the bearer token, in order of preference: Authorization Header,
	 * POST and GET.
	 *
	 * NB: Resource servers MUST accept tokens via the Authorization scheme
	 * (http://tools.ietf.org/html/draft-ietf-oauth-v2-bearer-08#section-2).
	 *
	 * @todo Should we enforce TLS/SSL in this function?
	 *      
	 * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-bearer-08#section-2.1
	 * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-bearer-08#section-2.2
	 * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-bearer-08#section-2.3
	 */
	protected function getBearerToken(Request $request = NULL, $removeFromRequest = false) {
		if ($request === NULL) {
			$request = Request::createFromGlobals ();
		}
		
		$tokens = array ();
		
		$token = $this->getBearerTokenFromHeaders ( $request, $removeFromRequest );
		if ($token !== NULL) {
			$tokens [] = $token;
		}
		
		$token = $this->getBearerTokenFromPost ( $request, $removeFromRequest );
		if ($token !== NULL) {
			$tokens [] = $token;
		}
		
		$token = $this->getBearerTokenFromQuery ( $request, $removeFromRequest );
		if ($token !== NULL) {
			$tokens [] = $token;
		}
		
		if (count ( $tokens ) > 1) {
			$ex = new sf_AuthenticationException ( 'Only one method may be used to authenticate at a time (Auth header, GET or POST).' );
			$ex->setToken ( $token );
			throw $ex;
		}
		
		if (count ( $tokens ) < 1) {
			// Don't throw exception here as we may want to allow non-authenticated
			// requests.
			return NULL;
		}
		
		return reset ( $tokens );
	}
	
	/**
	 * Get the access token from the header
	 *
	 * Old Android version bug (at least with version 2.2)
	 * 
	 * @see http://code.google.com/p/android/issues/detail?id=6684
	 */
	protected function getBearerTokenFromHeaders(Request $request, $removeFromRequest) {
		$header = null;
		if (! $request->headers->has ( 'AUTHORIZATION' )) {
			// The Authorization header may not be passed to PHP by Apache;
			// Trying to obtain it through apache_request_headers()
			if (function_exists ( 'apache_request_headers' )) {
				$headers = apache_request_headers ();
				
				// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
				$headers = array_combine ( array_map ( 'ucwords', array_keys ( $headers ) ), array_values ( $headers ) );
				
				if (isset ( $headers ['Authorization'] )) {
					$header = $headers ['Authorization'];
				}
			}
		} else {
			$header = $request->headers->get ( 'AUTHORIZATION' );
		}
		
		if (! $header) {
			return NULL;
		}
		
		if (! preg_match ( '/' . preg_quote ( self::TOKEN_BEARER_HEADER_NAME, '/' ) . '\s(\S+)/', $header, $matches )) {
			return NULL;
		}
		
		$token = $matches [1];
		
		if ($removeFromRequest) {
			$request->headers->remove ( 'AUTHORIZATION' );
		}
		
		return $token;
	}
	
	/**
	 * Get the token from POST data
	 */
	protected function getBearerTokenFromPost(Request $request, $removeFromRequest) {
		if ($request->getMethod () != 'POST') {
			return NULL;
		}
		
		$contentType = $request->server->get ( 'CONTENT_TYPE' );
		
		if ($contentType && $contentType != 'application/x-www-form-urlencoded') {
			return NULL;
		}
		
		if (! $token = $request->request->get ( self::TOKEN_PARAM_NAME )) {
			return NULL;
		}
		
		if ($removeFromRequest) {
			$request->request->remove ( self::TOKEN_PARAM_NAME );
		}
		
		return $token;
	}
	
	/**
	 * Get the token from the query string
	 */
	protected function getBearerTokenFromQuery(Request $request, $removeFromRequest)
	{
		if (!$token = $request->query->get(self::TOKEN_PARAM_NAME)) {
			return NULL;
		}
	
		if ($removeFromRequest) {
			$request->query->remove(self::TOKEN_PARAM_NAME);
		}
	
		return $token;
	}	
}