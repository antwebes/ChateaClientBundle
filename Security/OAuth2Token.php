<?php
namespace Ant\Bundle\ChateaClientBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class OAuth2Token extends AbstractToken
{

	private $access_token;
	private $refresh_token;
	private $scope;
	private $issueTime;
	private $tokenType;
	private $expiresIn;	
	private $client_id;
	private $secret;
	 	
	public function __construct($accessToken, $refresh_token = '', $tokenType = 'Bearer', $expiresIn = '3600', array $roles = array())
	{
		
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
	
	/**
	 * @param string $tokenSecret
	 */
	public function setTokenSecret($tokenSecret)
	{
		$this->tokenSecret = $tokenSecret;
	}
	
	/**
	 * @return null|string
	 */
	public function getTokenSecret()
	{
		return $this->tokenSecret;
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
}