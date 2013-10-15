<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Valid;

class ChannelFilter
{

    private $filter;

    public function __construct()
    {
        $this->filter['Ant\Bundle\ChateaClientBundle\Api\Query\Filter\FilterChannelType'] = null;
        $this->filter['Ant\Bundle\ChateaClientBundle\Api\Query\Filter\FilterChannelName'] = null;
    }
    public function setChannelType($channelType)
    {
        $this->filter['Ant\Bundle\ChateaClientBundle\Api\Query\Filter\FilterChannelType'] = $channelType;
    }

    /**
     * @return mixed
     */
    public function getChannelType()
    {
        return $this->filter['Ant\Bundle\ChateaClientBundle\Api\Query\Filter\FilterChannelType'];
    }

    /**
     * @param mixed $channelName
     */
    public function setChannelName($channelName)
    {
        $this->filter['Ant\Bundle\ChateaClientBundle\Api\Query\Filter\FilterChannelName'] = $channelName;
    }

    /**
     * @return mixed
     */
    public function getChannelName()
    {
        return $this->filter['Ant\Bundle\ChateaClientBundle\Api\Query\Filter\FilterChannelName'];
    }

    public function set($key, $value)
    {
        $this->filter[$key] = $value;
    }
    public function toArray()
    {
        return $this->filter;
    }
}