<?php
namespace Ant\Bundle\ChateaClientBundle\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticationException extends AuthenticationException
{
	/**
	 * @param key[optional]
	 * @param code[optional]
	 * @param previous[optional]
	 */
	public function __construct ($key, $code = 0, $previous = null)
    {
    	parent::__construct($key, $code, $previous);
    }
    public function getMessageKey()
    {
        return $this->message;
    }
}