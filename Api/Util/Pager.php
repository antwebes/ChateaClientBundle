<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Util;

use Ant\Bundle\ChateaClientBundle\Manager\BaseManager;
use Ant\Bundle\ChateaClientBundle\Manager\ManagerCollection;
use Countable;
use IteratorAggregate;


class Pager implements  Countable, IteratorAggregate
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

    function __construct(BaseManager $manager, CommandInterface $command, $page = 1, $limit)
    {
        $page = $page < 1 ? 1: $page;
        $this->limit    = $limit != null ? $limit : $manager->getLimit();
        $this->offset   = ($this->limit * ($page -1));

        $command->addParam('limit',$this->limit);
        $command->addParam('offset',$this->offset);

        $array_data = $manager->getManager()->execute($command);


        $this->total        = $array_data['total'];
        $this->offset       = $array_data['offset'];
        $this->_links       = $array_data['_links'];
        $resources          = $array_data['resources']?$array_data['resources']:null;
        $this->resources    = new ManagerCollection($manager, $resources);


        if(empty($resources))
        {
            $this->lastPage = 1;
            $this->page = 1;

        }else{
            $this->lastPage   = ($this->total % $this->limit == 0 )? $this->total / $this->limit: ( (int)($this->total / $this->limit )  +1 );
            $this->page =  $this->page > $this->lastPage ? $this->lastPage : $page;
        }
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
    /**
     * Retrieve an external iterator
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     *
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return $this->resources->getIterator();
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