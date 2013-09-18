<?php
namespace Ant\Bundle\ChateaClientBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Bridge\Monolog\Logger;

use Ant\ChateaClient\Client\Authentication;
use Ant\Bundle\ChateaClientBundle\Security\Authentication\Token\ChateaToken;
/**
 * ChateaProvider is the class for chatea client authentication ,
 * which process Token authentication.
 *
 * @author Xabier Fernándes <jjbier@gmail.com>
 */
class ChateaAuthenticationProvider implements AuthenticationProviderInterface
{
	private $userProvider;
	private $logger;
	private $userChecker;
	
	public function __construct(UserProviderInterface $userProvider, UserCheckerInterface $userChecker, Logger $logger)
	{
		$this->logger = $logger;
		$this->userProvider = $userProvider;
		$this->userChecker = $userChecker;	
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
		$this->logger->addInfo(get_class($this).'::authenticate()::-IN-',array('token'=>$token));
		$user = $token->getUser();
		ld($user);
		
		if (!$this->supports($token)) {
			$this->logger->addDebug(get_class($this).'::authenticate()::-OUT-Token NOT Support',array('token'=>$token));
			return null;
		}
				
		$userFromService = $this->userProvider->loadUser($user);
		
		try {
			$this->userChecker->checkPreAuth($userFromService);
			$this->checkAuthentication($userFromService, $token);
			
		}catch (BadCredentialsException $ex)
		{
			$this->logger->addDebug(get_class($this).'::authenticate()::-Error',array('BadCredentialsException'=>$ex));
			throw $ex;
		}
		$autheticatedToken = new ChateaToken($user);
		
		$this->logger->addInfo(get_class($this).'::authenticate()::-OUT-Authenticated');		
		$this->logger->addDebug(get_class($this).'::authenticate()::-OUT-',array('AutheticatedToken'=>$autheticatedToken));
		return $autheticatedToken;
		
	}
	
	protected function checkAuthentication (UserInterface $user, TokenInterface $token)
	{
		return true;
	}
	public function supports(TokenInterface $token)
	{
		return $token instanceof ChateaToken;
	}	
}