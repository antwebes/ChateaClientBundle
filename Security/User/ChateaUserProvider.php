<?php
namespace Ant\Bundle\ChateaClientBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Bridge\Monolog\Logger;

use Ant\ChateaClient\Client\Authentication;
use Ant\ChateaClient\Client\AuthenticationException;
use Ant\ChateaClient\Client\Api;
/**
 * Esta clase se encarga de buscar el usuario en un servicio
 * 
 * @author ant3
 *
 */
class ChateaUserProvider  implements UserProviderInterface
{
	private $user;
	private $logger;
	
	public function __construct(Authentication $authenticationService, Logger $logger)
	{
		$this->logger = $logger;
		$this->authenticationService = $authenticationService;
	}	
	
	public function loadUserByUsername($username)
	{
		$this->logger->addInfo(get_class($this).'::loadUser()::-IN-',array('user'=>$username));
		return $this->loadUser($username);
	}	
	
	protected function loadUser($user)
	{
		$this->logger->addInfo(get_class($this).'::loadUser()::-IN-',array('authenticationService'=>$this->authenticationService));
		$this->logger->addDebug(get_class($this).'::loadUser()::-IN-',array('user'=>$user));
		try {
			$tokenResponse = $this->authenticationService->authenticate();
		}catch (AuthenticationException $ex)
		{
			$this->logger->addDebug(get_class($this).'::loadUser()::-ERROR-',array('AuthenticationException'=>$ex));
			
			$newEx = new UsernameNotFoundException(sprintf('User for Access Token does not exist or is invalid.'));
			
			$this->logger->addDebug(get_class($this).'::loadUser()::-ERROR-',array('UsernameNotFoundException'=>$newEx));
			
			throw $newEx;			
		}
		if ($tokenResponse) {
			$this->logger->addInfo(get_class($this).'::loadUser()::-OUT-',array('tokenResponse'=>$tokenResponse));
			return new ChateaUser($tokenResponse->getAccessToken(true), $this->authenticationService->getClientId());
		}
		
		$newEx = new UsernameNotFoundException(sprintf('User for Access Token "%s" does not exist or is invalid.', $access_token));
		$this->logger->addDebug(get_class($this).'::loadUser()::-ERROR-',array('UsernameNotFoundException'=>$newEx));
		$this->logger->addInfo(get_class($this).'::loadUser()::-OUT-',array('tokenResponse'=>$tokenResponse));
		throw $newEx;
		
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