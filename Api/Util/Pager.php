<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Util;

use IteratorAggregate;
use Countable;
use Ant\Bundle\ChateaClientBundle\Api\Persistence\ObjectRepository;
use Ant\Bundle\ChateaClientBundle\Api\Collection\ApiCollection;


class Pager implements  IteratorAggregate, Countable
{
    protected
        $page = 1,
        $lastPage = 1,
        $size = 0,
        $maxPerPage,
        $nbResults = 0,
        $repository = null,
        $results = null,
        $filter = null;

    function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
        $this->results    = $this->getResults();
        $this->nbResults  = $this->results->getTotal();
        $this->lastPage   = ($this->nbResults % $this->size == 0 )? $this->nbResults / $this->size: ( (int)($this->nbResults / $this->size )  +1 );
    }


    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    public function getRepository()
    {
        return $this->repository;
    }
    public function cleanFilter()
    {
        $this->filter = '';
    }
    public function setFilter(array $filter = null)
    {
        $this->filter = $filter;
    }
    /**
     * Get the collection of results in the page
     *
     * @return ApiCollection A collection of results
     */
    public function getResults()
    {
        if (null === $this->results) {
            $this->results = $this->getRepository()
                ->findAll($this->page,$this->filter);
            $this->size    = $this->results->count();

        }
        return $this->results;
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
        if($this->size == 0){
            $this->size = $this->getResults()->count();
        }
        return $this->size;
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
        if($this->nbResults == 0){
            $this->nbResults =  $this->getResults()->getTotal();
        }
        return $this->nbResults;
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
     * Set the current page number (First page is 1).
     *
     * @param int $page
     *
     * @return void
     */
    public function setPage($page)
    {
        $this->results = null;
        $this->size = 0;
        if( $page == 0)
        {
          $this->page = 1;

        }else if($page > $this->getLastPage()){
            $this->page = $this->getLastPage();
        }else{
            $this->page = $page;
        }

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
    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return $this->getResult()->getIterator();
    }
}