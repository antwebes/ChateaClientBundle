<?php
namespace Ant\Bundle\ChateaClientBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Ant\Bundle\ChateaClientBundle\Security\Authentication\Token\OAuth2Token;

/**
 * OAuth2Provider is the class for chatea client authentication ,
 * which process Token authentication.
 *
 * @author Xabier Fernándes <jjbier@gmail.com>
 */
class OAuth2Provider implements AuthenticationProviderInterface
{
	private $userProvider;

	public function __construct(UserProviderInterface $userProvider)
	{
		$this->userProvider = $userProvider;
	}	
	/**
	 * Attempts to authenticate a TokenInterface object.
	 *
	 * @param TokenInterface $token The TokenInterface instance to authenticate
	 *
	 * @return TokenInterface An authenticated TokenInterface instance, never null
	 *
	 * @throws AuthenticationException if the authentication fails
	 */
	public function authenticate(TokenInterface $token)
	{
		$user = $this->userProvider->loadUserByUsername($token->getUsername());
		
		if ($user && !$token->isExpired()) {
			$authenticatedToken = new OAuth2Token($token->getRawToken(), $user->getRoles());
			$authenticatedToken->setUser($user);
			
			return $authenticatedToken;
		}
		
		throw new AuthenticationException('The OAuth2 authentication failed.');		
	}
	
	public function supports(TokenInterface $token)
	{
		return $token instanceof OAuth2Token;
	}	
}