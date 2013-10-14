<?php
namespace Ant\Bundle\ChateaClientBundle\Security\User;

use Ant\ChateaClient\Client\Authentication;
use Ant\ChateaClient\Client\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider implements ChateaUserProviderInterface
{
    private $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function loadUser($username, $password)
    {
        if (empty($username)) {
            throw new \InvalidArgumentException('The username cannot be empty.');
        }

        if(empty($password)) {
            throw new \InvalidArgumentException('The password cannot be empty.');
        }

        try {
            $json = $this->authentication->withUserCredentials($username, $password);
            return $this->mapJsonToUser($json, $username);
        } catch (AuthenticationException $e) {
            throw new UsernameNotFoundException(sprintf('Incorrect username or password for %s ', $username),30,$e);
        }
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
        // TODO: Implement refreshUser() method.
    }

    /**
     * Whether this provider supports the given user class
     *
     * @param string $class
     *
     * @return Boolean
     */
    public function supportsClass($class)
    {
        return $class === ' Ant\Bundle\ChateaClientBundle\Security\User\User';
    }

    protected function mapJsonToUser($json, $username)
    {
        $object = json_decode($json);

        return new User(
            $username,
            $object->access_token,
            $object->refresh_token,
            $object->token_type,
            $object->expires_in,
            $object->scope
        );
    }
}