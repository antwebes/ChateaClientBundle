<?php
namespace Ant\Bundle\ChateaClientBundle\Repositories;

class Filter
{
    protected  $filed;
    protected $value;
    protected $name;

    function __construct($name, $filed, $value = '')
    {
        $this->filed = $filed;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @param mixed $filed
     */
    public function setFiled($filed)
    {
        $this->filed = $filed;
    }

    /**
     * @return mixed
     */
    public function getFiled()
    {
        return $this->filed;
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
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

}