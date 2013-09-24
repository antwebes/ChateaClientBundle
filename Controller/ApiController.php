<?php

namespace Ant\Bundle\ChateaClientBundle\Controller;

class ApiController extends ChateaClientController
{

    public function indexAction()
    {
        return $this->render('ChateaClientBundle:Api:index.html.html');
    }

}