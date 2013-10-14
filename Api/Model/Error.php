<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

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