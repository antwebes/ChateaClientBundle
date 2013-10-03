<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ant3
 * Date: 2/10/13
 * Time: 18:18
 * To change this template use File | Settings | File Templates.
 */

namespace Ant\Bundle\ChateaClientBundle\Repositories;


class FilterChannelType  extends  Filter
{

    function __construct($value='')
    {
        parent::__construct('FilterChannelType','channelType', $value);
    }
}