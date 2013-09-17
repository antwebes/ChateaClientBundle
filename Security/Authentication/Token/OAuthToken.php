<?php
namespace Ant\Bundle\ChateaClientBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * OAuthToken class.
 * 
 * @author Xabier Fernández Rodríguez <jjbier@gmail.com>
 *
 */
class OAuthToken extends AbstractToken
{
	/**
	 * @var string
	 */
	protected $token;

	public function setToken($token)
	{
		$this->token = $token;
	}

	public function getToken()
	{
		return $this->token;
	}

	public function getCredentials()
	{
		return $this->token;
	}
}