<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Util;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;

use Countable;
use Ant\Bundle\ChateaClientBundle\Api\Collection\ApiCollection;


class Pager implements  Countable
{
    protected
    	$total,
    	$limit,
    	$offset,
    	$_links,
    	$resources,
        $page = 1,
        $lastPage = 1,
        $filters;

    function __construct($manager, $method, $page = 1, $limit=1, array $filters = null)
    {
        if($page < 1){
            $page = 1;
        }

        $offset = ($limit * ($page -1));
        $array_data = call_user_func_array(array($manager,$method),array($limit,$offset,$filters));
        $this->total = $array_data['total'];
        $this->limit = $array_data['limit'];
        $this->offset = $array_data['offset'];
        $this->_links = $array_data['_links'];
        $this->resources = $array_data['resources'];
        if($page > 1 && empty($this->resources)){
            throw new \InvalidArgumentException("The page field is incorrect");
        }

        $this->lastPage   = ($this->resources % $this->limit == 0 )? $this->total / $this->limit: ( (int)($this->total / $this->limit )  +1 );
        $this->page = $page;
    }

    /**
     * Get the collection of results in the page
     *
     * @return ApiCollection A collection of results
     */
    public function getResources()
    {
    	return $this->resources;
    }

    public function getTotalPages()
    {
        return $this->lastPage;
    }

    /**
     * Total elemets in actual page
     * @return int
     */
    public function getSize()
    {

        return $this->limit;
    }
    /**
     * Count elements in all pages
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *
     * The return value is cast to an integer.
     */
    public function count()
    {
        return $this->total;
    }

    /**
     * Get current page.
     *
     * @return int
     */
    public function getPage()
    {

        return $this->page;
    }

    /**
     * Get the number of the first page
     *
     * @return int Always 1
     */
    public function getFirstPage()
    {
        return 1;
    }
    /**
     * Get the number of the last page
     *
     * @return int
     */
    public function getLastPage()
    {
        return $this->lastPage;
    }
    /**
     * get previous id
     *
     * @return mixed $prev
     */
    public function getPrev()
    {
        if ($this->getPage() != $this->getFirstPage()) {
            $prev = $this->getPage() - 1;
        } else {
            $prev = false;
        }

        return $prev;
    }
    /**
     * get next id
     *
     * @return mixed $next
     */
    public function getNext()
    {
        if ($this->getPage() != $this->getLastPage()) {
            $next = $this->getPage() + 1;
        } else {
            $next = false;
        }

        return $next;
    }
    /**
     * get an array of previous id's
     *
     * @param int $nb_links
     *
     * @return array $links
     */
    public function getLinks($nb_links = 6)
    {
        $links = array();
        $tmp = $this->page - floor($nb_links / 2);
        $check = $this->lastPage - $nb_links + 1;
        $limit = ($check > 0) ? $check : 1;
        $begin = ($tmp > 0) ? (($tmp > $limit) ? $limit : $tmp) : 1;

        $i = (int) $begin;
        while (($i < $begin + $nb_links) && ($i <= $this->lastPage)) {
            $links[] = $i++;
        }
        return $links;
    }
}