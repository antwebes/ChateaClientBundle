<?php
namespace Ant\Bundle\ChateaClientBundle\Security\User;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Psr\Log\LoggerInterface;
use Ant\ChateaClient\Http\IHttpClient;
use Ant\ChateaClient\Client\AuthUserCredentials;
use Ant\ChateaClient\Client\AuthenticationException;
use Ant\ChateaClient\OAuth2\OAuth2ClientUserCredentials;
use Ant\ChateaClient\OAuth2\RefreshToken;
use Ant\ChateaClient\Client\Authentication;


class ChateaUserProvider implements ChateaUserProviderInterface
{

    private $httpClient;
    private $logger;
    private $client_id;
    private $secret;
    private $authenticator;
    /**
     * 
     * @param IHttpClient $httpClient
     * @param unknown $client_id
     * @param unknown $secret
     * @param LoggerInterface $logger
     * @throws \InvalidArgumentException
     */
    public function __construct(IHttpClient $httpClient, $client_id, $secret, LoggerInterface $logger)
    {
        if (empty($httpClient)) {
            throw new \InvalidArgumentException('The username cannot be empty.');
        }
        if (empty($client_id)) {
            throw new \InvalidArgumentException('The username cannot be empty.');
        }
        if (empty($secret)) {
            throw new \InvalidArgumentException('The username cannot be empty.');
        }                
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->client_id = $client_id;
        $this->secret = $secret;
        $this->authenticator = null;
            
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
        throw new \Exception("this method is not soported");
    }


    public function loadUser($username, $password)
    {
        if (empty($username)) {
            throw new \InvalidArgumentException('The username cannot be empty.');
        }    	
        
        if (empty($password)) {
            throw new \InvalidArgumentException('The password cannot be empty.');
        }        
        
        try {
        	$user = new OAuth2ClientUserCredentials($this->client_id,$this->secret,$username,$password);
        	$this->authenticator = new AuthUserCredentials($user, $this->httpClient);
        	$tokenResponse = $this->authenticator->authenticate();
        	
        	$accessToken   = $tokenResponse->getAccessToken(true);
        	$refreshToken  = $tokenResponse->getRefreshToken(true);
        	$tokenType     = $tokenResponse->getTokenType(true);
        	$expiresIn     = $tokenResponse->getExpiresIn();
        	$scopes        = $tokenResponse->getScope();
        	        	 	        	
			$user = new User($username,$accessToken,$refreshToken,$tokenType,$expiresIn,$scopes);
			
			$this->logger->info(get_class($this)."::loadUser()-OUT-", array('UserInterface'=>$user));
			return $user;
			
        }catch (AuthenticationException $ex )
        {
    		$ex = new UsernameNotFoundException(sprintf('Incorrect username or password for %s ', $username),30,$ex);
    		$this->logger->info(get_class($this)."::loadUser()-ERROR-", array('UsernameNotFoundException'=>$ex));
    		throw $ex;	  	
        }
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
        if (!$user instanceof User || !is_string($user->getRefreshToken()) || 0 >= strlen($user->getRefreshToken())){
            
            $ex = new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
            $this->logger->debug(get_class($this)."::refreshUser()-OUT-", array('UnsupportedUserException'=>$ex));
            throw $ex;
        }

        try {
            
            $tokenResponse = Authentication::updateWithRefreshToken(
                    $this->httpClient, 
                    $this->client_id, 
                    $this->secret, 
                    $user->getRefreshToken()
            );
            if ( $tokenResponse ){                                 
                $accessToken   = $tokenResponse->getAccessToken(true);
                $refreshToken  = $tokenResponse->getRefreshToken(true);
                $tokenType     = $tokenResponse->getTokenType(true);
                $expiresIn     = $tokenResponse->getExpiresIn();
                $scopes        = $tokenResponse->getScope();
                 
                $user = new User($user->getUsername(),$accessToken,$refreshToken,$tokenType,$expiresIn,$scopes);
                	
                $this->logger->info(get_class($this)."::refreshUser()-OUT-", array('UserInterface'=>$user));
                return $user;
            }
            
            $exception = new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
            $this->logger->info(get_class($this)."::refreshUser()-OUT-", array('UsernameNotFoundException'=>$exception));
            throw $exception;                 
            	
        }catch (AuthenticationException $ex )
        {
            $ex = new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $user->getUsername()),30,$ex);
            $this->logger->info(get_class($this)."::refreshUser()-OUT-", array('UsernameNotFoundException'=>$ex));
            throw $ex;
        }        
    }

    public function supportsClass($class)
    {
        $this->logger->info(get_class($this)."::supportsClass()-OUT-", array('class'=>$class));
        return $class === ' Ant\Bundle\ChateaClientBundle\Security\User\User';
    }
}