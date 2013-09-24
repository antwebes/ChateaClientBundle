<?php
namespace Ant\Bundle\ChateaClientBundle\Security\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Ant\Bundle\ChateaClientBundle\Security\User\User as ChateaUser;
use Ant\Bundle\ChateaClientBundle\Security\User\ChateaUserProviderInterface;
use Ant\Bundle\ChateaClientBundle\Security\Token\ChateaToken;
use Psr\Log\LoggerInterface;

class ChateaAuthenticationProvider implements AuthenticationProviderInterface
{
    private $chateaUserProvider;
    private $hideUserNotFoundExceptions;
    private $userChecker;
    private $providerKey;
    private $logger;
    /**
     * Constructor.
     * 
     * @param \Ant\Bundle\ChateaClientBundle\Security\User\ChateaUserProviderInterface $chateaUserProvider
     * @param UserCheckerInterface $userChecker                An UserCheckerInterface interface
     * @param string               $providerKey                A provider key
     * @param Boolean              $hideUserNotFoundExceptions Whether to hide user not found exception or not
     *
     * @throws \InvalidArgumentException
     */    
    public function __construct(ChateaUserProviderInterface $chateaUserProvider, UserCheckerInterface $userChecker, $providerKey, LoggerInterface $logger, $hideUserNotFoundExceptions = true)
    {
    
        if (empty($providerKey)) {
            throw new \InvalidArgumentException('$providerKey must not be empty.');
        }
        
        $this->userChecker = $userChecker;
        $this->providerKey = $providerKey;
        $this->hideUserNotFoundExceptions = $hideUserNotFoundExceptions;        
        $this->chateaUserProvider = $chateaUserProvider;
        $this->logger =$logger;
    }
        
    /**
     * Attempts to authenticate a TokenInterface object.
     *
     * @param TokenInterface $token The TokenInterface instance to authenticate
     *
     * @return TokenInterface An authenticated TokenInterface instance, never null
     *
     * @throws AuthenticationException if the authentication fails
     */    
    public function authenticate(TokenInterface $token)
    {
        $this->logger->info(get_class($this)."::authenticate()-INI-",array('TokenInterface'=>$token));
        if (!$this->supports($token)) {
            $this->logger->debug(get_class($this)."::authenticate()-OUT-NOT SPORT TOKEN",array('TokenInterface'=>$token));
            return null;
        }
    
        $username = $token->getUsername();
        if (empty($username)) {
            $username = 'NONE_PROVIDED';
        }
    
        try {
            $user = $this->retrieveUser($username, $token);
        } catch (UsernameNotFoundException $notFound) {
            if ($this->hideUserNotFoundExceptions) {
                $this->logger->debug(get_class($this)."::authenticate()-ERROR",array('UsernameNotFoundException'=>$notFound));
                throw new BadCredentialsException('Bad credentials', 0, $notFound);
            }
            $notFound->setUsername($username);
            $this->logger->debug(get_class($this)."::authenticate()-ERROR",array('UsernameNotFoundException'=>$notFound));
            throw $notFound;
        }
    
        if (!$user instanceof UserInterface) {
            $authException = new AuthenticationServiceException('retrieveUser() must return a UserInterface.');
            $this->logger->debug(get_class($this)."::authenticate()-ERROR",array('AuthenticationServiceException'=>$authException));
            throw $authException;
        }
    
        try {
            $this->userChecker->checkPreAuth($user);
            $this->checkAuthentication($user, $token);
            $this->userChecker->checkPostAuth($user);
        } catch (BadCredentialsException $e) {
            if ($this->hideUserNotFoundExceptions) {
                throw new BadCredentialsException('Bad credentials', 0, $e);
            }
            $this->logger->debug(get_class($this)."::authenticate()-ERROR",array('BadCredentialsException'=>$e));
            throw $e;
        }
        
        $authenticatedToken = new ChateaToken($user, $token->getCredentials(), $this->providerKey, $user->getAccesToken(), $user->getRoles());
        $authenticatedToken->setAttributes($token->getAttributes());
        $this->logger->info(get_class($this)."::authenticate()-OUT",array('ChateaToken'=>$authenticatedToken));
        return $authenticatedToken;
    }    


    /**
     * Retrieves the user from an implementation-specific location.
     *
     * @param string $username The username to retrieve
     * @param ChateaToken $token    The Token
     *
     * @throws UsernameNotFoundException|\Exception
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationServiceException
     * @return UserInterface The user
     *
     */
	protected function retrieveUser($username, ChateaToken $token)
	{
	    $this->logger->info(get_class($this)."::retrieveUser()-INI-",array('username'=>$username,'ChateaToken'=>$token));
	    $user = $token->getUser();
	    if ($user instanceof UserInterface) {
	        $this->logger->debug(get_class($this)."::retrieveUser()-ERROR- NOT instanceof UserInterface",array('username'=>$username,'ChateaToken'=>$token));
	        return $user;
	    }
	    	    
	        try {
            $user = $this->chateaUserProvider->loadUser($username,$token->getCredentials());

            if (!$user instanceof UserInterface) {
                $this->logger->debug(get_class($this)."::retrieveUser()-ERROR- NOT instanceof UserInterface",array('username'=>$username,'ChateaToken'=>$token));
                $this->logger->debug(get_class($this)."::retrieveUser()-ERROR- NOT instanceof UserInterface",array('username'=>$username,'ChateaToken'=>$token));
                $authException = new AuthenticationServiceException('The user provider must return a UserInterface object.');
                throw $authException;
            }
            $this->logger->info(get_class($this)."::retrieveUser()-OUT- ",array('user'=>$user));
            return $user;
        } catch (UsernameNotFoundException $notFound) {
            $notFound->setUsername($username);
            $this->logger->info(get_class($this)."::retrieveUser()-ERROR- ",array('UsernameNotFoundException'=>$notFound));
            throw $notFound;
        } catch (\Exception $repositoryProblem) {
            $ex = new AuthenticationServiceException($repositoryProblem->getMessage(), 0, $repositoryProblem);
            $ex->setToken($token);
            $this->logger->info(get_class($this)."::retrieveUser()-ERROR- ",array('repositoryProblem'=>$ex));
            throw $ex;
        }
	}
	
	/**
	 * Does additional checks on the user and token (like validating the
	 * credentials).
	 *
	 * @param UserInterface        $user  The retrieved UserInterface instance
	 * @param ChateaToken $token   The ChateaToken token to be authenticated
	 *
	 * @throws BadCredentialsException if the credentials could not be validated
	*/
    protected function checkAuthentication(UserInterface $user, ChateaToken $token)
    {
        $this->logger->info(get_class($this)."::checkAuthentication()-INI-",array('user'=>$user,'ChateaToken'=>$token));
        $currentUser = $token->getUser();
        
        if ($currentUser instanceof UserInterface) {
            if ($currentUser->getPassword() !== $user->getPassword()) {
                $badCredential = new BadCredentialsException('The credentials were changed from another session.');
                $this->logger->debug(get_class($this)."::checkAuthentication()-ERROR-",array('BadCredentialsException'=>$badCredential));                
                throw $badCredential;
            }
        } else {
            if ("" === ($presentedPassword = $token->getCredentials())) {
                $badCredential = new BadCredentialsException('The presented password cannot be empty.'); 
                $this->logger->debug(get_class($this)."::checkAuthentication()-ERROR-",array('BadCredentialsException'=>$badCredential));
                throw $badCredential;
            }           
        }
        $this->logger->info(get_class($this)."::checkAuthentication()-OUT-",array('user'=>$user,'ChateaToken'=>$token));
    }
    
    public function supports(TokenInterface $token)
    {
        $support = $token instanceof ChateaToken && $this->providerKey === $token->getProviderKey();
        $this->logger->info(get_class($this)."::supports()-INI-",array('supports'=>$support));
        return $support;
    }    
}