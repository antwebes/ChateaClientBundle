<?php
namespace Ant\Bundle\ChateaClientBundle\Api\Collection;

use Ant\Common\Collections\ArrayCollection;

class ApiCollection extends  ArrayCollection
{

    private $total = 0;


    public function getTotal()
    {
        if($this->total < $this->count()){
            $this->total = $this->count();
        }
        return $this->total;
    }

    public function setTotal($total)
    {

        $this->total = $total;
    }
}