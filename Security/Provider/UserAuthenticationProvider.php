<?php
namespace Ant\Bundle\ChateaClientBundle\Security\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;

use Symfony\Component\Security\Core\Authentication\Provider\UserAuthenticationProvider as sf_UserAuthenticationProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Ant\Bundle\ChateaClientBundle\Security\User\User as ChateaUser;
use Ant\Bundle\ChateaClientBundle\Security\User\ChateaUserProviderInterface;

class UserAuthenticationProvider extends sf_UserAuthenticationProvider
{
    private $chateaUserProvider;

    /**
     * Constructor.
     *
     * @param \Ant\Bundle\ChateaClientBundle\Security\User\ChateaUserProviderInterface $chateaUserProvider
     * @param UserCheckerInterface $userChecker                An UserCheckerInterface interface
     * @param string $providerKey                A provider key
     * @param Boolean $hideUserNotFoundExceptions Whether to hide user not found exception or not
     *
     * @internal param \Ant\Bundle\ChateaClientBundle\Security\Provider\ChateaProviderInterface $chateaProvider An UserProviderInterface instance
     */
	public function __construct(ChateaUserProviderInterface $chateaUserProvider, UserCheckerInterface $userChecker, $providerKey, $hideUserNotFoundExceptions = true)
	{

		parent::__construct($userChecker, $providerKey, $hideUserNotFoundExceptions);
		$this->chateaUserProvider = $chateaUserProvider;
	}

    /**
     * Retrieves the user from an implementation-specific location.
     *
     * @param string $username The username to retrieve
     * @param UsernamePasswordToken $token    The Token
     *
     * @throws UsernameNotFoundException|\Exception
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationServiceException
     * @return UserInterface The user
     *
     */
	protected function retrieveUser($username, UsernamePasswordToken $token)
	{
	    
	    $user = $token->getUser();
	    if ($user instanceof UserInterface) {
	        return $user;
	    }
	    	    
	        try {
            $user = $this->chateaUserProvider->loadUser($username,$token->getCredentials());

            if (!$user instanceof UserInterface) {
                throw new AuthenticationServiceException('The user provider must return a UserInterface object.');
            }
            return $user;
        } catch (UsernameNotFoundException $notFound) {
            $notFound->setUsername($username);
            throw $notFound;
        } catch (\Exception $repositoryProblem) {
            $ex = new AuthenticationServiceException($repositoryProblem->getMessage(), 0, $repositoryProblem);
            $ex->setToken($token);
            throw $ex;
        }
	}
	
	/**
	 * Does additional checks on the user and token (like validating the
	 * credentials).
	 *
	 * @param UserInterface         $user  The retrieved UserInterface instance
	 * @param UsernamePasswordToken $token The UsernamePasswordToken token to be authenticated
	 *
	 * @throws BadCredentialsException if the credentials could not be validated
	*/
    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {
        $currentUser = $token->getUser();
        if ($currentUser instanceof UserInterface) {
            if ($currentUser->getPassword() !== $user->getPassword()) {
                throw new BadCredentialsException('The credentials were changed from another session.');
            }
        } else {
            if ("" === ($presentedPassword = $token->getCredentials())) {
                throw new BadCredentialsException('The presented password cannot be empty.');
            }
        }
    }
}