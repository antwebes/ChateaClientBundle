<?php

namespace Ant\Bundle\ChateaClientBundle\Security\Firewall;



use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Bridge\Monolog\Logger;

use Ant\Bundle\ChateaClientBundle\Security\Authentication\Token\ChateaToken;

class ChateaListener implements ListenerInterface {
	
	
	private $securityContext;
	private $authenticationManager;
	private $logger;
	private $csrfProvider;
	private $options;
	public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, CsrfProviderInterface $csrfProvider, Logger $logger,  array $options = array() ) 
	{
		$this->logger = $logger;
		$this->securityContext = $securityContext;
		$this->authenticationManager = $authenticationManager;
		$this->csrfProvider = $csrfProvider;
		$this->options = array_merge(array(
				'check_path'                     => '/login_check',
				'login_path'                     => '/login',
				'always_use_default_target_path' => false,
				'default_target_path'            => '/',
				'target_path_parameter'          => '_target_path',
				'use_referer'                    => false,
				'failure_path'                   => null,
				'failure_forward'                => false,
				'require_previous_session'       => true,
				'post_only'						 => true
		), $options);		
		
	}
	protected function requiresAuthentication(Request $request)
	{
	    if ($this->options['post_only'] && !$request->isMethod('POST')) {
	    	$this->logger->debug(sprintf('Authentication method not supported: %s.', $request->getMethod()),array('Request',$request));
            return false;
        }
	}
		
	public function handle(GetResponseEvent $event) 
	{
		$this->logger->addInfo(get_class($this).'::handle()::-IN-',array('event'=>$event));

		$request = $event->getRequest();

        if (!$this->requiresAuthentication($request)) {
            return;
        }
        
        if (!$request->hasSession()) {
        	throw new \RuntimeException('This authentication method requires a session.');
        }

        try {
        	if ($this->options['require_previous_session'] && !$request->hasPreviousSession()) {
        		throw new SessionUnavailableException('Your session has timed out, or you have disabled cookies.');
        	}        
        	
        	$username = trim($request->request->get($this->options['username_parameter'], null, true));
        	$password = $request->request->get($this->options['password_parameter'], null, true);
        	
        	$this->logger->addDebug(get_class($this).'::handle()::-USER-',array('username'=>$username));
        	$this->logger->addDebug(get_class($this).'::handle()::-USER-',array('password'=>$password));
        	
        	$request->getSession()->set(SecurityContextInterface::LAST_USERNAME, $username);
        	
        	
        	$authToken = $this->authenticationManager->authenticate(new ChateaToken($username, $password));
        	$this->logger->addDebug(get_class($this).'::handle()::-USER-',array('ChateaToken'=>new ChateaToken($username, $password)));
        	$this->logger->addInfo(get_class($this).'::handle()::-OUT-',array('AuthToken'=>$authToken));
        	return $authToken;
        	        	
        } catch (AuthenticationException $e) {
        	$this->logger->addDebug(get_class($this).'::handle()::-ERROR-',array('AuthenticationException'=>$e));
        	$response = $this->onFailure($event, $request, $e);
        }
        
        $event->setResponse($response);  
        $this->logger->addInfo(get_class($this).'::handle()::-OUT-',array('event'=>$event));
	}

}