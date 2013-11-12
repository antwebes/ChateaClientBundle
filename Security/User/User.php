<?php
namespace Ant\Bundle\ChateaClientBundle\Security\User;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

class User implements AdvancedUserInterface
{
    private $username;
    private $accessToken;
    private $refreshToken;
    private $tokenType;
    private $enabled;
    private $expired_at;
    private $accountNonLocked;
    private $roles;

    public function __construct($username, $accessToken, $refreshToken, $tokenType = 'Bearer', $expiresIn = 0, array $scopes = array() )
    {
        $this->username = $username;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->tokenType = $tokenType;


        $this->setExpiresIn($expiresIn);

        $this->enabled = true;
        $this->accountNonLocked = true;


        $this->roles = array();
        $this->scopes = $scopes;
        foreach ($scopes as $scope) {
            array_push($this->roles, 'ROLE_' . strtoupper($scope));
        }
        array_push($this->roles, 'ROLE_API_USER');
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
        return null;
    }
    /**
     * {@inheritdoc}
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }
    public function getTokenType()
    {
        return $this->tokenType;
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

    public function getExpiresAt()
    {
        return $this->expired_at;
    }

    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return Boolean true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return Boolean true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return $this->accountNonLocked;
    }
    /**
     * Checks whether the user's credentials (token) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return Boolean true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return $this->expired_at > time() ;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return Boolean true if the user is enabled, false otherwise
     *
     * @see DisabledException
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
    }

    /**
     * Sets the access token
     * @param string $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Sets the number of seconds from now when the access token expires
     * @param int expiresIn
     */
    public function setExpiresIn($expiresIn)
    {
        $this->expired_at = $expiresIn > 0 ? time() + $expiresIn : 0;
    }

    /**
     * Sets the refresh token
     * @param string $refreshToken
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }
}