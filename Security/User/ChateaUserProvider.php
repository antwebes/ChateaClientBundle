<?php
namespace Ant\Bundle\ChateaClientBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Ant\ChateaClient\Client\Authentication;
use Ant\ChateaClient\Client\AuthenticationException;
use Ant\ChateaClient\Client\Api;

class ChateaUserProvider  implements UserProviderInterface
{
	private $authenticationService;
	
	public function __construct(Authentication $authenticationService)
	{
		$this->authenticationService = $authenticationService;
	}	
	
	public function loadUserByUsername($username)
	{
		return $this->loadUser();
	}	
	
	protected function loadUser()
	{
		try {
			$tokenResponse = $this->authenticationService->authenticate();
		}catch (AuthenticationException $ex)
		{
			throw new UsernameNotFoundException(sprintf('User for Access Token does not exist or is invalid.'));			
		}
		if ($tokenResponse) {
			return new ChateaUser($tokenResponse->getAccessToken(true), $this->authenticationService->getClientId());
		}
		
		throw new UsernameNotFoundException(sprintf('User for Access Token "%s" does not exist or is invalid.', $access_token));		
	}
	
	public function refreshUser(UserInterface $user)
	{
		if (!$user instanceof ChateaUser) {
			throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
		}
	
		return $this->loadUserByUsername($user->getUsername());
	}
	
	public function supportsClass($class)
	{
		return $class === 'Ant\Bundle\ChateaClientBundle\Security\User\ChateaUser';
	}	
}