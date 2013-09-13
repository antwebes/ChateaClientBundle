<?php
namespace Ant\Bundle\ChateaClientBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class OAuth2Token extends AbstractToken
{

	private $access_token;
	private $refresh_token;
	private $issueTime;
	private $tokenType;
	private $expiresIn;	
	private $scope;
	
	/**
	 * @var array
	 */
	private $rawToken;
		
	/**
	 * @param string|array $accessToken The OAuth access token
	 * @param array        $roles       Roles for the token
	 */	 	
	public function __construct(array $roles = array())
	{
        parent::__construct($roles);

        $this->setRawToken($accessToken);

        parent::setAuthenticated(count($roles) > 0);
	}	
	
	/**
	 * @param string $accessToken The OAuth access token
	 */
	public function setAccessToken($accessToken)
	{
		$this->accessToken = $accessToken;
	}
	
	/**
	 * @return string
	 */
	public function getAccessToken()
	{
		return $this->accessToken;
	}	
	

	/**
	 * @param string $refreshToken The OAuth refresh token
	 */
	public function setRefreshToken($refreshToken)
	{
		$this->refreshToken = $refreshToken;
	}
	
	/**
	 * @return string
	 */
	public function getRefreshToken()
	{
		return $this->refreshToken;
	}

	/**
	 * @param integer $expiresIn The duration in seconds of the access token lifetime
	 */
	public function setExpiresIn($expiresIn)
	{
		$this->issueTime = time();
		$this->expiresIn = $expiresIn;
	}
	
	/**
	 * @return integer
	 */
	public function getExpiresIn()
	{
		return $this->expiresIn;
	}	
	
	public function getScope()
	{
		return $this->scope;
	}
	public function setScope($scope = null)
	{
		$this->scope = $scope;
	}
	/**
	 * Returns if the `access_token` is expired.
	 *
	 * @return boolean True if the `access_token` is expired.
	 */
	public function isExpired()
	{
		if (null === $this->expiresIn) {
			return false;
		}
	
		return ($this->issueTime + ($this->expiresIn - time())) < 30;
	}	
	
	/**
	 * @param array|string $token The OAuth token
	 */
	public function setRawToken($token)
	{
		if (is_array($token)) {
			if (isset($token['access_token'])) {
				$this->accessToken = $token['access_token'];
			} elseif (isset($token['oauth_token'])) {
				$this->accessToken = $token['oauth_token'];
			}
	
			if (isset($token['refresh_token'])) {
				$this->refreshToken = $token['refresh_token'];
			}
	
			if (isset($token['expires_in'])) {
				$this->setExpiresIn($token['expires_in']);
			} elseif (isset($token['oauth_expires_in'])) {
				$this->setExpiresIn($token['oauth_expires_in']);
			}
	
			if (isset($token['scope'])) {
				$this->scope = $token['scope'];
			}
	
			$this->rawToken = $token;
		} else {
			$this->accessToken = $token;
			$this->rawToken    = array('access_token' => $token);
		}
	}
	
	/**
	 * @return array
	 */
	public function getRawToken()
	{
		return $this->rawToken;
	}	
}