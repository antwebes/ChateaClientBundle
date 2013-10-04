<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Persistence;

interface ObjectManager
{

    /**
     * Gets the repository for a class.
     *
     * @param string $entityName
     *
     * @return ObjectRepository
     */
    public function getRepository($entityName);
}