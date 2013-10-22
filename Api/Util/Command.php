<?php
namespace Ant\Bundle\ChateaClientBundle\Api\Util;


class Command  implements CommandInterface
{

    private $name;
    private $params;


    function __construct($name, $params)
    {
        $this->params = $params;
        $this->name = $name;
    }


    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }


    public function addParam($key, $value)
    {
        $this->params[$key]=$value;
    }

}