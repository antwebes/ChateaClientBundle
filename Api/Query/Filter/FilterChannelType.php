<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Query\Filter;


class FilterChannelType  extends  ApiFilter
{

    function __construct($value='')
    {
        parent::__construct(get_class($this),'channelType', $value);
    }
}