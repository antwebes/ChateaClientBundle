<?php
namespace Ant\Bundle\ChateaClientBundle\Security\Authentication;

use Ant\Bundle\ChateaClientBundle\Security\User\ChateaUserProviderInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Provider\UserAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationProvider extends UserAuthenticationProvider
{
    private $userProvider;

    /**
     * Constructor.
     *
     * @param ChateaUserProviderInterface $userprovider               An ChateaUserProviderInterface
     * @param UserCheckerInterface        $userChecker                An UserCheckerInterface interface
     * @param string                      $providerKey                A provider key
     * @param Boolean                     $hideUserNotFoundExceptions Whether to hide user not found exception or not
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(ChateaUserProviderInterface $userProvider, UserCheckerInterface $userChecker, $providerKey, $hideUserNotFoundExceptions = true)
    {
        $this->userProvider = $userProvider;
        parent::__construct($userChecker, $providerKey, $hideUserNotFoundExceptions);
    }

    /**
     * Retrieves the user from an implementation-specific location.
     *
     * @param string $username The username to retrieve
     * @param UsernamePasswordToken $token    The Token
     *
     * @return UserInterface The user
     *
     * @throws AuthenticationException if the credentials could not be validated
     */
    protected function retrieveUser($username, UsernamePasswordToken $token)
    {
        $user = $token->getUser();

        if ($user instanceof UserInterface) {
            return $user;
        }

        return $this->userProvider->loadUser($username,$token->getCredentials());
    }

    /**
     * Does additional checks on the user and token (like validating the
     * credentials).
     *
     * @param UserInterface $user  The retrieved UserInterface instance
     * @param UsernamePasswordToken $token The UsernamePasswordToken token to be authenticated
     *
     * @throws AuthenticationException if the credentials could not be validated
     */
    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {
        // TODO: Implement checkAuthentication() method.
    }
}