<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ant3
 * Date: 2/10/13
 * Time: 18:29
 * To change this template use File | Settings | File Templates.
 */

namespace Ant\Bundle\ChateaClientBundle\Repositories;


class FilterPage extends  Filter
{
    private $total;
    private $limit;

    function __construct($value='', $total='',$limit = '')
    {
        parent::__construct('FilterPage','&page', $value);
        $this->limit = $limit;
        $this->total = $total;
    }

    public function getValue()
    {
        //first page
        if($this->value <= 1 ){
            return 1;
        }
        // last page
        if($this->value >= $this->getLastPage() )
        {
            return $this->getLastPage();
        }
        // other
        return $this->value;
    }

    private function getLastPage()
    {
        return (( $this->total % $this->limit) == 0 )?$this->total / $this->limit: ($this->total % $this->limit) +1;
    }
}