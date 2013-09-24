<?php
namespace Ant\Bundle\ChateaClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{

    public function loginAction()
    {
        $logger = $this->container->get('antwebes_logger');

        if ($logger == null) {
            $logger = $this->get('logger');
        }
        $logger->addInfo(get_class($this) . "::loginAction()-IN");

        $request = $this->getRequest();
        $session = $request->getSession();

        $logger->addDebug(get_class($this) . "::loginAction()-CHECK-Request: " . $request->__toString());
        $logger->addDebug(get_class($this) . "::loginAction()-CHECK-Session: ", array('session' => $session->all()));

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $logger->addDebug(get_class($this) . "::loginAction()-TRACE- SecurityContext::AUTHENTICATION_ERROR ");
            $error = $request->attributes->get(
                    SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $logger->addDebug(get_class($this) . "::loginAction()-TRACE- NOT SecurityContext::AUTHENTICATION_ERROR");
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        $csrf = $this->get('form.csrf_provider');
        $token = $csrf->generateCsrfToken('authenticate');

        $logger->addDebug(get_class($this) . "::loginAction()-TRACE- RETURN VIEW ");
        $logger->addInfo(get_class($this) . "::loginAction()-OUT");

        return $this->render('ChateaClientBundle:Security:login.html.twig', array(
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
                    'csrf_token' => $token
                        )
        );
    }

    public function loginCheckAction()
    {
        return new \Symfony\Component\HttpFoundation\Response("loginCheckAction");
    }

    public function logoutAction()
    {
        return new \Symfony\Component\HttpFoundation\Response("logoutAction");
    }

}
