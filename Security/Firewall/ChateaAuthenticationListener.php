<?php
namespace Ant\Bundle\ChateaClientBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Ant\Bundle\ChateaClientBundle\Security\Exception\AuthenticationException;
use Ant\Bundle\ChateaClientBundle\Security\Token\ChateaToken;

use Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider;

class ChateaAuthenticationListener extends AbstractAuthenticationListener
{

    private $csrfProvider;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     *            A SecurityContext instance
     * @param AuthenticationManagerInterface $authenticationManager
     *            An AuthenticationManagerInterface instance
     * @param SessionAuthenticationStrategyInterface $sessionStrategy            
     * @param HttpUtils $httpUtils
     *            An HttpUtilsInterface instance
     * @param string $providerKey            
     * @param AuthenticationSuccessHandlerInterface $successHandler            
     * @param AuthenticationFailureHandlerInterface $failureHandler
     * @param CsrfProviderInterface $csrfProvider
     * @param LoggerInterface $logger            
     * @param array $options
     *            An array of options for the processing of a
     *            successful, or failed authentication attempt     
     *            A LoggerInterface instance
     * @param EventDispatcherInterface $dispatcher
     *            An EventDispatcherInterface instance
     *            
     * @throws \InvalidArgumentException
     */
    public function __construct(
        SecurityContextInterface $securityContext, 
        AuthenticationManagerInterface $authenticationManager, 
        SessionAuthenticationStrategyInterface $sessionStrategy, 
        HttpUtils $httpUtils, $providerKey, 
        AuthenticationSuccessHandlerInterface $successHandler, 
        AuthenticationFailureHandlerInterface $failureHandler,
        array $options = array(),
        $csrfProvider,                
        LoggerInterface $logger,               
        EventDispatcherInterface $dispatcher = null         
        )
    {
        parent::__construct($securityContext, $authenticationManager, $sessionStrategy, $httpUtils, $providerKey, $successHandler, $failureHandler, array_merge(array(
            'username_parameter' => '_username',
            'password_parameter' => '_password',
            'csrf_parameter' => 'login_csrf_token',
            'intention' => 'authenticate',
            'post_only' => true
        ), $options), $logger, $dispatcher);
        
        $this->csrfProvider = $csrfProvider;
    }

    /**
     * Performs authentication.
     *
     * @param Request $request
     *            A Request instance
     *
     * @throws \Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException
     * @throws \Ant\Bundle\ChateaClientBundle\Security\Exception\AuthenticationException
     * @return TokenInterface Response null token, null if full authentication is not possible, or a Response
     *
     */
    protected function attemptAuthentication(Request $request)
    {
        $this->logger->info(get_class($this) . "::attemptAuthentication()::INI-", array('Request' => $request));
        $this->logger->debug(get_class($this) . "::attemptAuthentication()::INI-", array('Request' => $request->__toString()));
        
        if ($this->options['post_only'] && ! $request->isMethod('POST')) {
            $ex = new AuthenticationException('Invalid HTTP Method use POST.');
            $this->logger->debug(get_class($this) . "::attemptAuthentication()::INI-", array('AuthenticationException' => $ex));
            throw $ex;
        }
        

        $csrfToken = $request->request->get($this->options['csrf_parameter'], null, true);

        if (false === $this->csrfProvider->isCsrfTokenValid($this->options['intention'], $csrfToken)) {
            
            $ex = new InvalidCsrfTokenException('Invalid CSRF token.');
            $this->logger->debug(get_class($this) . "::attemptAuthentication()::INI-", array('InvalidCsrfTokenException' => $ex));
            $this->logger->debug(get_class($this) . "::attemptAuthentication()::INI-", array('CsrfToken' => $csrfToken));
            
            throw $ex;            
        }
        
        $username = trim($request->request->get($this->options['username_parameter'], null, true));
        $password = $request->request->get($this->options['password_parameter'], null, true);
                
        
        $request->getSession()->set(SecurityContextInterface::LAST_USERNAME, $username);
        
        $token = $this->authenticationManager->authenticate(new ChateaToken($username, $password, $this->providerKey));

        $this->logger->info(get_class($this) . "::attemptAuthentication()::OUT-", array('TokenInterface' => $token));
        $this->logger->debug(get_class($this) . "::attemptAuthentication()::OUT-", array('Request' => $token->__toString()));
        return $token;
    }
    
    /**
     * Whether this request requires authentication.
     *
     * The default implementation only processes requests to a specific path,
     * but a subclass could change this to only authenticate requests where a
     * certain parameters is present.
     *
     * @param Request $request
     *
     * @return Boolean
     */
    protected function requiresAuthentication(Request $request)
    {
        if ($this->options['post_only'] && ! $request->isMethod('POST')) {
            $this->logger->info(get_class($this). "::requiresAuthentication()::INI-", array('Request' => $request));
            return false;
        }
        return parent::requiresAuthentication($request);
    }
}