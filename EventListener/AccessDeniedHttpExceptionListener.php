<?php
/*
 * This file is part of the  chatBoilerplate package.
 *
 * (c) Ant web <ant@antweb.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ant\Bundle\ChateaClientBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * Class AccessDeniedHttpExceptionListener
 *
 * @package Ant\Bundle\ChateaClientBundle\EventListener;
 */
class AccessDeniedHttpExceptionListener implements  EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $securityContext;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var string
     */
    private $routeIdUserNotValidated;

    /**
     * AccessDeniedHttpExceptionListener constructor.
     *
     * @param TokenStorageInterface $securityContext
     * @param UrlGeneratorInterface $urlGenerator
     * @param string $routeIdUserNotValidated
     */
    public function __construct(
        TokenStorageInterface $securityContext,
        UrlGeneratorInterface $urlGenerator,
        $routeIdUserNotValidated
    ) {
        $this->securityContext = $securityContext;
        $this->urlGenerator = $urlGenerator;
        $this->routeIdUserNotValidated = $routeIdUserNotValidated;
    }


    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException',
        );
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if($exception instanceof AccessDeniedHttpException){
            $token = $this->securityContext->getToken();
            /** @var \Ant\Bundle\ChateaSecureBundle\Security\User\User $user */
            $user = $token != null ? $token->getUser(): null;

            if($token != null && $token->isAuthenticated() && $user != null && is_object($user) && false === $user->isValidated() ){

                $url = $this->urlGenerator->generate($this->routeIdUserNotValidated,array('userId'=>$user->getId()));
                $response = new RedirectResponse($url, 302);
                $event->setResponse($response);
            }
        }
    }
}