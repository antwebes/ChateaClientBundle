<?php
namespace Ant\Bundle\ChateaClientBundle\Api\Util;


interface CommandInterface
{

    /**
     * @return string the name of command
     */
    public function getName();

    /**
     * @return array list arguments
     */
    public function getParams();

    public function addParam($key, $value);
}