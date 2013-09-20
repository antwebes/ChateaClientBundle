<?php
namespace Ant\Bundle\ChateaClientBundle\Security\User;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

class User implements AdvancedUserInterface
{
	private $username;
    private $accessToken;
    private $refreshToken;
    private $tokenType;
    private $expiresIn;    
    private $scopes;
	private $enabled;
	private $created_at;
	private $accountNonExpired;
	private $credentialsNonExpired;
	private $accountNonLocked;
	private $roles;
	
	public function __construct($username, $accessToken, $refreshToken, $tokenType = 'Bearer', $expiresIn = 0, array $scopes = array() )
	{
	    //array $roles = array(), $enabled = true, $userNonExpired = true, $credentialsNonExpired = true, $userNonLocked = true
	    
		if (empty($username)) {
			throw new \InvalidArgumentException('The username cannot be empty.');
		}
		
		$this->username = $username;
		$this->accessToken = $accessToken;
		$this->refreshToken = $refreshToken;
		$this->tokenType = $tokenType;
		$this->expiresIn = $expiresIn;
		$this->created_at = time();
		$this->enabled = true;
		$this->accountNonLocked = true;		
		$this->accountNonExpired = ($expiresIn == 0 );
		$this->credentialsNonExpired = ($expiresIn == 0 );		
		$this->roles = array();
		$this->scopes = $scopes;
		foreach ($scopes as $scope) {
		    $this->roles[] = 'ROLE_' . strtoupper($scope);
		}
		
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getRoles()
	{
		return $this->roles;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPassword()
	{
	    return $this->accessToken;
	}	
	/**
	 * {@inheritdoc}
	 */
	public function getAccesToken()
	{
		return $this->accessToken;
	}
	public function getRefreshToken()
	{
		return $this->refreshToken;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getSalt()
	{
		return null;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getUsername()
	{
		return $this->username;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function isAccountNonExpired()
	{
		return $this->accountNonExpired;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function isAccountNonLocked()
	{
		return $this->accountNonLocked;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function isCredentialsNonExpired()
	{
		return $this->credentialsNonExpired;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function isEnabled()
	{
		return $this->enabled;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function eraseCredentials()
	{
	    $this->accessToken = null;
	}	
}