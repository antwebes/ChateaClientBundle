<?php
namespace Ant\Bundle\ChateaClientBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Psr\Log\LoggerInterface;

class UserProvider implements UserProviderInterface 
{
	
	protected $users;
	protected  $logger;
	
	public function __construct(LoggerInterface $logger)
	{				
		$this->logger = $logger;
		$this->users = json_decode(file_get_contents(__DIR__.'/users.json'), true);
		$this->logger->info(get_class($this)."::construct()-INI-");
	}
		
	/**
	 * Loads the user for the given username.
	 *
	 * This method must throw UsernameNotFoundException if the user is not
	 * found.
	 *
	 * @param string $username The username
	 *
	 * @return UserInterface
	 *
	 * @see UsernameNotFoundException
	 *
	 * @throws UsernameNotFoundException if the user is not found
	 *
	 */
	public function loadUserByUsername($username)
	{
		$this->logger->info(get_class($this)."::loadUserByUsername()-INI-", array('username'=>$username));
		
		if (isset($this->users[$username])) {
			$user = new User($username, $this->users[$username]['password'], $this->users[$username]['roles']);
			$this->logger->info(get_class($this)."::loadUserByUsername()-OUT-", array('UserInterface'=>$user));
			return $user;
		}
		$ex = new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
		$this->logger->info(get_class($this)."::loadUserByUsername()-OUT-", array('UsernameNotFoundException'=>$ex));
		throw $ex;		
	}
	
	/**
	 * Refreshes the user for the account interface.
	 *
	 * It is up to the implementation to decide if the user data should be
	 * totally reloaded (e.g. from the database), or if the UserInterface
	 * object can just be merged into some internal array of users / identity
	 * map.
	 * @param UserInterface $user
	 *
	 * @return UserInterface
	 *
	 * @throws UnsupportedUserException if the account is not supported
	*/
	public function refreshUser(UserInterface $user)
	{
		if (!$user instanceof User) {
			throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
		}
		
		return $this->loadUserByUsername($user->getUsername());		
	}
	
	/**
	 * Whether this provider supports the given user class
	 *
	 * @param string $class
	 *
	 * @return Boolean
	*/
	public function supportsClass($class)
	{
		
		return $class === ' Ant\Bundle\ChateaClientBundle\Security\User\User';
	}	
}
