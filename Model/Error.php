<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ant3
 * Date: 30/09/13
 * Time: 18:13
 * To change this template use File | Settings | File Templates.
 */

namespace Ant\Bundle\ChateaClientBundle\Model;


class Error
{
    private $code;
    private $mensage;
    private $trace = array();
    function __construct($mensage, $code = 0, array $trace = array() )
    {
        $this->code = $code;
        $this->mensage = $mensage;
        $this->trace = $trace;
    }

    public function getCode()
    {
        return $this->code;
    }
    public function getMessage()
    {
        return $this->mensage;
    }
    public function getTrace()
    {
        return $this->trace;
    }

    public function __toString()
    {
        return $this->mensage;
    }

}