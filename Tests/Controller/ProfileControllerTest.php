<?php
/**
 * User: José Ramón Fernandez Leis
 * Email: jdeveloper.inxenio@gmail.com
 * Date: 17/09/15
 * Time: 11:17
 */

namespace Ant\Bundle\ChateaClientBundle\Tests\Controller;

use Ant\Bundle\ChateaClientBundle\Controller\ProfileController;
use Ant\Bundle\ChateaClientBundle\Tests\app\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class ProfileControllerTest extends \PHPUnit_Framework_TestCase
{
    private $containerMock;
    private $sfContainer;
    private $routerMock;
    private $userManagerMock;
    private $countryManagerMock;
    private $citiesManagerMock;
    private $templatingMock;
    private $securityContext;
    private $profileController;
    private $tokenStorage;
    private $token;
    private $sessionMock;

    protected function setUp()
    {
        parent::setUp();

        $this->containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->routerMock = $this->getMock('Symfony\Component\Routing\RouterInterface');
        $this->templatingMock = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->tokenStorage = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        $this->securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');
        $this->token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $this->userManagerMock = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Manager\UserManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->countryManagerMock = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Manager\CountryManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->citiesManagerMock = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Manager\CityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->sessionMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
            ->setMethods(array('clear', 'start', 'getId', 'isStarted', 'has', 'set'))
            ->disableOriginalConstructor()
            ->getMock();

        $this->fakeCallMethod($this->containerMock, 'has', true, 'security.token');
        $this->fakeCallMethod($this->tokenStorage, 'getToken', $this->token);
//        $this->fakeCallMethod($this->securityContext, 'getToken', $this->token);
        $this->fakeCallMethod($this->token, 'getUser', $this->createSymfonyUser());

        $kernel = new AppKernel('test', true);
        $kernel->boot();
        $this->sfContainer = $kernel->getContainer();
        $this->sfContainer->set('security.token_storage', $this->tokenStorage);
        $this->sfContainer->set('api_users', $this->userManagerMock);
        $this->sfContainer->set('api_countries', $this->countryManagerMock);
        $this->sfContainer->set('api_cities', $this->citiesManagerMock);
        $this->sfContainer->set('security.context', $this->securityContext);
        $this->sfContainer->set('session', $this->sessionMock);

        $this->profileController = new ProfileController();
        $this->profileController->setContainer($this->sfContainer);
    }

    public function testEditCityAction()
    {
        $this->fakeCallMethod($this->userManagerMock, 'findById', $this->createApiUser(), 2);

        $request = new Request();

        $this->profileController->editCityAction($request);
    }

    public function testPostEditCityAction()
    {
        $user = $this->createApiUser();
        $city = $this->createCity(3);
        $country = $this->createCountry(2);
        $csrfTokenProvider = $this->getMock('Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface');

        $csrfTokenProvider->expects($this->any())
            ->method('generateCsrfToken')
            ->will($this->returnValue('abc'));

        $csrfTokenProvider->expects($this->any())
            ->method('isCsrfTokenValid')
            ->will($this->returnValue(true));

        $this->fakeCallMethod($this->userManagerMock, 'findById', $user, 2);
        $this->fakeCallMethod($this->citiesManagerMock, 'findById', $city, 3);
        $this->fakeCallMethod($this->countryManagerMock, 'findByCode', $country, 2);

        $this->userManagerMock->expects($this->once())
            ->method('updateUserCity')
            ->with($user, $country, $city);

        $this->sfContainer->set('form.csrf_provider', $csrfTokenProvider);

        $request = new Request();
        $request->setMethod('POST');
        $request->request->set('edit_city', array('city' => 3, 'searchCountry' => '{"country_code":"ES","has_cities":true,"id":2510769}', 'country' => 2, '_token' => 'abc'));
        $request->request->set('_token', 'abc');

        $this->profileController->editCityAction($request);
    }

    /**
     * Mocks the return of an method
     * @param $object the object where to mock the method
     * @param $method the method to mock
     * @param $return whtat to return
     * @param $with the argument expected
     * @param null $when when, how many times we expect it to be called
     */
    protected function fakeCallMethod($object, $method, $return, $with = null, $when = null)
    {
        if($when === null){
            $when = $this->any();
        }

        if($with == null){
            $object
                ->expects($when)
                ->method($method)
                ->will($this->returnValue($return));
        }else{
            $object
                ->expects($when)
                ->method($method)
                ->with($with)
                ->will($this->returnValue($return));
        }
    }

    private function createSymfonyUser()
    {
        $user = $this->getMockBuilder('Ant\Bundle\ChateaSecureBundle\Security\User\User')
            ->disableOriginalConstructor()
            ->getMock();

        $user->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));

        return $user;
    }

    private function createApiUser()
    {
        $user = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Model\User')
            ->disableOriginalConstructor()
            ->getMock();

        $this->fakeCallMethod($user, 'isEnabled', true);
        $this->fakeCallMethod($user, 'getId', 2);
        $this->fakeCallMethod($user, 'getCity', $this->createCity(12345));

        return $user;
    }

    private function createCountry($id)
    {
        $country = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Model\Country')
            ->disableOriginalConstructor()
            ->getMock();

        $this->fakeCallMethod($country, 'getId', $id);

        return $country;
    }

    private function createCity($id)
    {
        $city = $this->getMockBuilder('Ant\Bundle\ChateaClientBundle\Api\Model\City')
            ->disableOriginalConstructor()
            ->getMock();

        $this->fakeCallMethod($city, 'getId', $id);

        return $city;
    }
}