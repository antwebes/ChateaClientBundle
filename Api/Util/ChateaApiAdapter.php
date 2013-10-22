<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ant3
 * Date: 22/10/13
 * Time: 8:51
 * To change this template use File | Settings | File Templates.
 */

namespace Ant\Bundle\ChateaClientBundle\Api\Util;

use  Pagerfanta\Adapter\AdapterInterface;

class ChateaApiAdapter implements AdapterInterface
{

    private $api_method;
    private $filter;
    private $order;
    private $total;

    public function __construct($api_method, array $filter = null, $order = null)
    {

        if($api_method == null){
            throw new \InvalidArgumentException("the field api_method is required");
        }
        $this->api_method = $api_method;
        $this->filter = $filter;
        $this->order = $order;

    }

    /**
     * Returns the number of results.
     *
     * @return integer The number of results.
     */
    function getNbResults()
    {
        $this->api_method(1,0,$this->filter,$this->order);
        return $this->total;
    }

    /**
     * Returns an slice of the results.
     *
     * @param integer $offset The offset.
     * @param integer $length The length.
     *
     * @return array|\Traversable The slice.
     */
    function getSlice($offset, $length)
    {
        $api_elemnts = $this->api_method($length,$offset,$this->filter,$this->order);

        if(!array_key_exists('resources',$api_elemnts)){
            throw new \InvalidArgumentException("resources filed is required");
        }
        if(!array_key_exists('limit',$api_elemnts)){
            throw new \InvalidArgumentException("limit filed is required");
        }
        if(!array_key_exists('offset',$api_elemnts)){
            throw new \InvalidArgumentException("offset filed is required");
        }
        if(!array_key_exists('total',$api_elemnts)){
            throw new \InvalidArgumentException("total filed is required");
        }

        $this->total = $api_elemnts['total'];

        return $api_elemnts['resources'];

    }
}