<?php
namespace Ant\Bundle\ChateaClientBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

class ChateaUser implements UserInterface, EquatableInterface {
	
	private $client_id;
	private $secret;
	private $scopes;
	private $access_token;
	private $username;
	private $password;
	private $email;
	
	public function __construct($access_token, $client_id, $secret = NULL, array $scopes = array())
	{
		$this->scopes = array();
		foreach ($scopes as $scope) {
			array_push($this->scopes, 'ROLE_' . strtoupper($scope));
		}
		//basic ROLE
		array_push($this->scopes, 'ROLE_API_USER');
				
	}
	
	public function getRoles() 
	{
		return $this->scopes;	
	}
	public function getPassword() 
	{
		return null;
	
	}
	public function getSalt() 
	{
		return null;
	
	}
	public function getUsername() 
	{
		return $this->username;
	}
	public function eraseCredentials() {
		$this->access_token = null;
		$this->scopes = null;
	}
		
	public function isEqualTo(UserInterface $user) {
		if (!$user instanceof ChateaUser) {
			return false;
		}

		if ($this->password !== $user->getPassword()) {
			return false;
		}

		if ($this->getSalt() !== $user->getSalt()) {
			return false;
		}
		if ($this->username !== $user->getUsername()) {
			return false;
		}

		return true;
	}
}
