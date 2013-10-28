<?php
namespace Ant\Bundle\ChateaClientBundle\Security\User;

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
    public function testLoadUserWhenAuthenticatorThrowsUsernameNotFoundException()
    {
        $exception = $this->getUsernameNotFoundException();

        $this->authenticator
            ->expects($this->once())
            ->method('withUserCredentials')
            ->with('username', 'password')
            ->will($this->throwException($exception));

        $this->userProvider->loadUser('username', 'password');
    }

    public function testRefreshUser()
    {
        $this->authenticator
            ->expects($this->never())
            ->method('withRefreshToken');

        $user = $this->getExpectedMockedUser();

        $this->assertEquals($user, $this->userProvider->refreshUser($user));
    }

    public function testRefreshUserWithExpiredAccessToken()
    {
        $responseToken = $this->getResponseToken();
        $this->authenticator
            ->expects($this->once())
            ->method('withRefreshToken')
            ->with("12HHIIK")
            ->will($this->returnValue($responseToken));

        $user = $this->getExpectedMockedUser(false);

        $this->assertEquals($user, $this->userProvider->refreshUser($user));
        $this->assertTrue($user->isCredentialsNonExpired());
    }

    private function getResponseToken()
    {
        return array(
                    "access_token" => "321IUKKL",
                    "expires_in" => 3600,
                    "token_type" => "password",
                    "scope" => "role_1",
                    "refresh_token" => "12HHIIK"
                );
    }

    private function getExpectedUser($isCredentialsNonExpired = true)
    {
        return new User('username', '321IUKKL', '12HHIIK', 'password', 3600, array('role_1'));
    }

    private function getExpectedMockedUser($isCredentialsNonExpired = true)
    {
        $now = time();
        TimeHelper::$time = $now;
        $user = new User('username', '321IUKKL', '12HHIIK', 'password', 3600, array('role_1'));

        if($isCredentialsNonExpired){
            TimeHelper::$time = $now + 1000;
        }else{
            TimeHelper::$time = $now + 3700;
        }
        
        return $user;
    }

    private function getUsernameNotFoundException()
    {
        return $this->getMockBuilder('Symfony\Component\Security\Core\Exception\UsernameNotFoundException')
            ->disableOriginalConstructor()
            ->getMock();
    }
}

if(!function_exists('Ant\Bundle\ChateaClientBundle\Security\User\time')) {
    function time()
    {
        if(TimeHelper::$time != null){
            return TimeHelper::$time;
        }else{
            return \time();
        }
    }
}

class TimeHelper
{
    public static $time = null;
}