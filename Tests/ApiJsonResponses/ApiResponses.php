<?php
/*
 * This file is part of the  chatBoilerplate package.
 *
 * (c) Ant web <ant@antweb.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ant\Bundle\ChateaClientBundle\Tests\ApiJsonResponses;

/**
 * Class ApiResponses
 *
 * @package Ant\Bundle\ChateaClientBundle\Tests\ApiJsonResponses
 */
class ApiResponses
{
    public function getUserWithFilterPartialName()
    {
        return $this->getContentFromJsonFile(__DIR__ . '/Responses/getUserWithFilterPartilaName.json');
    }

    public function findUserById()
    {
        return $this->getContentFromJsonFile(__DIR__ . '/Responses/findUserById.json');
    }

    public function findFindChannelById()
    {
        return $this->getContentFromJsonFile(__DIR__ . '/Responses/findChannelById.json');
    }

    public function findCityById()
    {
        return $this->getContentFromJsonFile(__DIR__ . '/Responses/findCityById.json');
    }

    public function findPhotoById()
    {
        return $this->getContentFromJsonFile(__DIR__ . '/Responses/findPhotoById.json');
    }

    public function findUserProfileById()
    {
        return $this->getContentFromJsonFile(__DIR__ . '/Responses/findUserProfileById.json');
    }

    /**
     * @return mixed
     */
    protected function getContentFromJsonFile($path)
    {
        $string = file_get_contents($path);

        return json_decode($string, true);
    }
}