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
            $data = $this->authentication->withUserCredentials($username, $password);
            return $this->mapJsonToUser($data, $username);
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
        if (!$user instanceof User || !is_string($user->getRefreshToken()) || 0 >= strlen($user->getRefreshToken())){
            
            $ex = new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
            $this->logger->debug(get_class($this)."::refreshUser()-OUT-", array('UnsupportedUserException'=>$ex));
            throw $ex;
        }

        if($user->isCredentialsNonExpired()){
            return $user;
        }

        try {
            $data = $this->authentication->withRefreshToken($user->getRefreshToken());
            return $this->mapJsonToUser($data, $user->getUsername());
        } catch (AuthenticationException $e) {
            throw new UsernameNotFoundException(sprintf('Incorrect username or password for %s ', $username),30,$e);
        }
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

    protected function mapJsonToUser($data, $username)
    {
        return new User(
            $username,
            $data['access_token'],
            $data['refresh_token'],
            $data['token_type'],
            $data['expires_in'],
            explode(',', $data['scope'])
        );
    }
}