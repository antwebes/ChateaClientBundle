<?php
namespace Ant\Bundle\ChateaClientBundle\Tests\Security\Authentication;

use Ant\Bundle\ChateaClientBundle\Security\Authentication\AuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User;

class AuthenticationProviderTest extends \PHPUnit_Framework_TestCase
{
    private $logger;
    private $userChecker;
    private $userProvider;
    private $authenticationProvider;
    private $providerKey = 'clase_proveedor';

    public function setUp()
    {
        $this->logger = $this->getMockForAbstractClass('Psr\Log\LoggerInterface');
        $this->userChecker = $this->getMockForAbstractClass('Symfony\Component\Security\Core\User\UserCheckerInterface');
        $this->userProvider = $this->getMockForAbstractClass('Ant\Bundle\ChateaClientBundle\Security\User\ChateaUserProviderInterface');

        $this->authenticationProvider = new AuthenticationProvider($this->userProvider, $this->userChecker, $this->providerKey);
    }

    public function testAuthenticateWithValidToken()
    {
        $token = new UsernamePasswordToken('usuario1', 'clave1', $this->providerKey);
        $this->userProvider
            ->expects($this->once())
            ->method('loadUser')
            ->with('usuario1', 'clave1')
            ->will($this->returnValue(new User('usuario1', 'clave1')));

        $tokenReturned = $this->authenticationProvider->authenticate($token);

        $user = $tokenReturned->getUser();
        $this->assertEquals('usuario1', $user->getUsername());
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\BadCredentialsException
     */
    public function testAuthenticateWithInvalidToken()
    {
        $token = new UsernamePasswordToken('usuario1', 'clave1', $this->providerKey);
        $this->userProvider
            ->expects($this->once())
            ->method('loadUser')
            ->with('usuario1', 'clave1')
            ->will($this->throwException(new UsernameNotFoundException(sprintf('Incorrect username or password for %s ', 'usuario1'),30, new \Exception())));

        $tokenReturned = $this->authenticationProvider->authenticate($token);

        $user = $tokenReturned->getUser();
    }
}