<?php
namespace Ant\Bundle\ChateaClientBundle\Api\Collection;

use Ant\Common\Collections\ArrayCollection;

class ApiCollection extends  ArrayCollection
{

    private $total = 0;
    private $page = 1;
    private $limit;

    function __construct($total, $page, $limit)
    {
        $this->total = $total;
        $this->limit = $limit;
        $this->page = $page;

        parent::__construct();
    }


    public function getTotal()
    {
        if($this->total < $this->count()){
            $this->total = $this->count();
        }
        return $this->total;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    public function setTotal($total)
    {

        $this->total = $total;
    }
}