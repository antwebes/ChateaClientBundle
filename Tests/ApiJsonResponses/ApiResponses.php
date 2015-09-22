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
        $string = file_get_contents(__DIR__.'/Responses/getUserWithFilterPartilaName.json');

        return json_decode($string, true);
    }
}