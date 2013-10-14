<?php
namespace Ant\Bundle\ChateaClientBundle\Tests\Security\User;

use Ant\Bundle\ChateaClientBundle\Security\User\UserProvider;
use Ant\Bundle\ChateaClientBundle\Security\User\User;
use Guzzle\Http\Exception\BadResponseException;

class UserProviderTest extends \PHPUnit_Framework_TestCase
{
    private $authenticator;
    private $userProvider;

    public function setUp()
    {
        $this->authenticator = $this->getMockBuilder('Ant\ChateaClient\Client\Authentication')
            ->disableOriginalConstructor()
            ->getMock();
        $this->userProvider = new UserProvider($this->authenticator);
    }

    public function testLoadUserThrowsExceptionIfUsernameIsNotGiven()
    {
        try {
            $this->userProvider->loadUser('', 'password');
        } catch (\InvalidArgumentException $e) {
            if($e->getMessage() == 'The username cannot be empty.')
                return;
        }

        $this->fail('Expected to raise an InvalidArgumentException');
    }

    public function testLoadUserThrowsExceptionIfPasswordIsNotGiven()
    {
        try {
            $this->userProvider->loadUser('username', '');
        } catch (\InvalidArgumentException $e) {
            if($e->getMessage() == 'The password cannot be empty.')
                return;
        }

        $this->fail('Expected to raise an InvalidArgumentException');
    }

    public function testLoadUser()
    {
        $responseToken = $this->getResponseToken();
        $user = $this->getExpectedUser();

        $this->authenticator
            ->expects($this->once())
            ->method('withUserCredentials')
            ->with('username', 'password')
            ->will($this->returnValue($responseToken));

        $this->assertEquals($user, $this->userProvider->loadUser('username', 'password'));
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadUserWhenAuthenticatorThrowsBadResponsException()
    {
        $exception = $this->getBadResponseException();

        $this->authenticator
            ->expects($this->once())
            ->method('withUserCredentials')
            ->with('username', 'password')
            ->will($this->throwException($exception));

        $this->userProvider->loadUser('username', 'password');
    }

    private function getResponseToken()
    {
        return '{
                    "access_token": "321IUKKL",
                    "expires_in": "3600",
                    "token_type": "password",
                    "scope": ["role_1"],
                    "refresh_token": "12HHIIK"
                }';
    }

    private function getExpectedUser()
    {
        return new User('username', '321IUKKL', '12HHIIK', 'password', '3600', array('role_1'));
    }

    private function getBadResponseException()
    {
        return $this->getMockBuilder('Ant\ChateaClient\Client\AuthenticationException')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
