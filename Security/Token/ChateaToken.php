<?php
namespace Ant\Bundle\ChateaClientBundle\Security\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class ChateaToken extends AbstractToken
{
	protected $accestoken;
	private $credentials;
	private $providerKey;
	
	/**
	 * Constructor.
	 *
	 * @param string          $user        The username (like a nickname, email address, etc.), or a UserInterface instance or an object implementing a __toString method.
	 * @param string          $credentials This usually is the password of the user
	 * @param string          $providerKey The provider key
	 * @param RoleInterface[] $roles       An array of roles
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct($user, $credentials, $providerKey, $accestoken = '', array $roles = array())
	{
	    parent::__construct($roles);
	    
	    if (empty($providerKey)) {
	        throw new \InvalidArgumentException('$providerKey must not be empty.');
	    }
	    
	    $this->setUser($user);
	    $this->credentials = $credentials;
	    $this->providerKey = $providerKey;
	    $this->accestoken = $accestoken;
	    parent::setAuthenticated(count($roles) > 0);
	    	    
	}	

	/**
	 * {@inheritdoc}
	 */
	public function setAuthenticated($isAuthenticated)
	{
	    if ($isAuthenticated) {
	        throw new \LogicException('Cannot set this token to trusted after instantiation.');
	    }
	
	    parent::setAuthenticated(false);
	}
	
	public function getCredentials()
	{
	    return $this->credentials;
	}
	
	public function getProviderKey()
	{
	    return $this->providerKey;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function eraseCredentials()
	{
	    parent::eraseCredentials();
	
	    $this->credentials = null;
	    $this->accestoken = null;
	}
	public function getAccesToken()
	{
		return $this->accestoken;
	}
	/**
	 * {@inheritdoc}
	 */
	public function serialize()
	{
	    return serialize(array($this->accestoken, $this->credentials, $this->providerKey, parent::serialize()));
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function unserialize($serialized)
	{
	    list($this->accestoken, $this->credentials, $this->providerKey, $parentStr) = unserialize($serialized);
	    parent::unserialize($parentStr);
	}
}