<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

class City
{
    /**
     * @var string
     */
    private $name;

    /**
     * Returns the name of the city
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name of the city
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}