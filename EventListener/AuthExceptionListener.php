<?php
namespace Ant\Bundle\ChateaClientBundle\EventListener;

use Ant\ChateaClient\Client\ApiException;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthExceptionListener
{
    private $annotationReader;
    private $urlGenerator;
    private $loginRoute;
    private $annotationClass = 'Ant\Bundle\ChateaClientBundle\Security\Authentication\Annotation\APIUser';

    function __construct(Reader $annotationReader, UrlGeneratorInterface $urlGenerator, $loginRoute)
    {
        $this->annotationReader = $annotationReader;
        $this->urlGenerator = $urlGenerator;
        $this->loginRoute = $loginRoute;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $controllerResolver = new ControllerResolver();
        $request = $event->getRequest();
        $controller = $controllerResolver->getController($event->getRequest());

        if($exception instanceof ApiException && $this->hasApiUserAnnotation($controller)){
            try{
                $request->getSession()->invalidate();
            }catch(\Exception $e){

            }

            $url = $this->urlGenerator->generate($this->loginRoute);
            $response = new RedirectResponse($url, 302);
            $event->setResponse($response);
        }
    }

    private function hasApiUserAnnotation($controller)
    {
        $object = new \ReflectionObject($controller[0]);
        $method = $object->getMethod($controller[1]);

        return $this->annotationReader->getMethodAnnotation($method, $this->annotationClass) != null ||
            $this->annotationReader->getClassAnnotation($object, $this->annotationClass) != null;
    }
}