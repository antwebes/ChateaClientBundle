<?php
namespace Ant\Bundle\ChateaClientBundle\Security;

use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * OAuth2Provider is the class for chatea client authentication ,
 * which process Token authentication.
 *
 * @author Xabier Fernándes <jjbier@gmail.com>
 */
class OAuth2Provider implements AuthenticationManagerInterface
{
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
		
	}
}