<?php

namespace Ant\Bundle\ChateaClientBundle\Controller;

use Ant\Bundle\ChateaClientBundle\Model\Error;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ChateaClientBundle:Default:index.html.html');
    }

    public function errorAction($message, $return='')
    {
        $errors = new Error($message,-1);
        return $this->render('ChateaClientBundle:Default:error.html.twig',array('errors' => $errors, 'return'=>$return));
    }
}
