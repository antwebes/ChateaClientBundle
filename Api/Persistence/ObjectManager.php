<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Persistence;

interface ObjectManager
{

    /**
     * Gets the manager for a class.
     *
     * @param string $entityName
     *
     * @return ObjectRepository
     */
    public function getManager($entityName);
}