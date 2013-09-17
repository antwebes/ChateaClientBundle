<?php
namespace Ant\Bundle\ChateaClientBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Ant\ChateaClient\Client\Authentication;
use Ant\Bundle\ChateaClientBundle\Security\Authentication\Token\OAuthToken;
/**
 * ChateaProvider is the class for chatea client authentication ,
 * which process Token authentication.
 *
 * @author Xabier Fernándes <jjbier@gmail.com>
 */
class ChateaProvider implements AuthenticationProviderInterface
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
		if (!$this->supports($token)) {
			return null;
		}
				
		$user = $this->userProvider->loadUserByUsername($token->getUsername());
		
		if ($user && $this->validate($token, $user)) {
			$authenticatedToken = new OAuthToken($user->getRoles());
			$authenticatedToken->setUser($user);
		
			return $authenticatedToken;
		}

		throw new AuthenticationException('The OAuth2 authentication failed.');		
	}
	
	protected function validate (TokenInterface $token, UserInterface $user)
	{
		return true;
	}
	public function supports(TokenInterface $token)
	{
		return $token instanceof OAuthToken;
	}	
}