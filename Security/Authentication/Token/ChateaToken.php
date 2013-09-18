<?php
namespace Ant\Bundle\ChateaClientBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * OAuthToken class.
 * 
 * @author Xabier Fernández Rodríguez <jjbier@gmail.com>
 *
 */
class ChateaToken extends AbstractToken
{
    private $credentials;
    private $accessToken;
    /**
     * Constructor.
     *
     * @param string          $user        The username (like a nickname, email address, etc.), or a UserInterface instance or an object implementing a __toString method.
     * @param string          $password    The password (credentials) of the user
     * @param RoleInterface[] $roles       An array of roles
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($user, $password, array $roles = array(),$accessToken = null)
    {
        parent::__construct($roles);

        $this->setUser($user);
        $this->credentials = $password;
        $this->accessToken = $accessToken;

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

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        parent::eraseCredentials();

        $this->credentials = null;
        $this->accessToken = null;
    }
	public function getAccessToken()
	{
		return $this->accessToken;
	}
    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array($this->credentials, $this->accessToken, parent::serialize()));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list($this->credentials, $this->accessToken, $parentStr) = unserialize($serialized);
        parent::unserialize($parentStr);
    }
}