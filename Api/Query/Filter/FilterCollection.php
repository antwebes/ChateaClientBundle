<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Query\Filter;

use Countable;
use Iterator;
use Ant\Common\Collections\ArrayCollection;

class FilterCollection implements Countable, Iterator
{
    /* Filter STATES */
    /**
     * A filter object is in CLEAN state when it has no changed parameters.
     */
    const FILTERS_STATE_CLEAN  = 1;

    /**
     * A filter object is in DIRTY state when it has changed parameters.
     */
    const FILTERS_STATE_DIRTY = 2;

    /**
     * Instances of enabled filters.
     *
     * @var array
     */
    private $enabledFilters = array();

    /**
     * @var string The filter hash from the last time the query was parsed.
     */
    private $filterHash;

    /**
     * @var integer $state The current state of this filter
     */
    private $filtersState = self::FILTERS_STATE_CLEAN;

    /**
     * An array containing the entries of this collection.
     *
     * @var ArrayCollection
     */
    private $_elements;

    private $filter_delimiter;
    /**
     * Initializes a new FileterCollection.
     *
     */
    public function __construct($delimiter = ',')
    {
        $this->filter_delimiter = $delimiter;
        $this->_elements = new ArrayCollection();
    }

    /**
     * Get all the enabled filters.
     *
     * @return array The enabled filters.
     */
    public function getEnabledFilters()
    {
        return $this->enabledFilters;
    }

    /**
     * Enables a filter from the collection.
     *
     * @param string $name Name of the filter.
     * @param mixed $value value of the filter.
     *
     * @throws \InvalidArgumentException If the filter does not exist.
     *
     * @return ApiFilter The enabled filter.
     */
    public function enable($name, $value)
    {
        if (null === $filterClass = $this->get($name)) {
            throw new \InvalidArgumentException("Filter '" . $name . "' does not exist.");
        }

        if (!isset($this->enabledFilters[$name])) {
            $filter = $this->get($name);
            $filter->setValue($value);
            $this->enabledFilters[$name] = $filter;

            // Keep the enabled filters sorted for the hash
            ksort($this->enabledFilters);

        }

        return $this->enabledFilters[$name];
    }
    /**
     * Disables a filter.
     *
     * @param string $name Name of the filter.
     *
     * @return ApiFilter The disabled filter.
     *
     * @throws \InvalidArgumentException If the filter does not exist.
     */
    public function disable($name)
    {
        // Get the filter to return it
        $filter = $this->get($name);

        unset($this->enabledFilters[$name]);

        return $filter;
    }
    /**
     * @return boolean True, if the filter collection is clean.
     */
    public function isClean()
    {
        return self::FILTERS_STATE_CLEAN === $this->filtersState;
    }

    /**
     * Generates a string of currently enabled filters to use for query.
     *
     * @return string
     */
    public function getHash()
    {
        // If there are only clean filters, the previous hash can be returned
        /*
        if (self::FILTERS_STATE_CLEAN === $this->filtersState) {
            return $this->filterHash;
        }
        */
        $filterHash = '';
        foreach ($this->enabledFilters as $filter) {

            $filterHash .= $filter->getFiled() .'='. $filter->getValue();

            if($filter != $this->last())
            {
                $filterHash .= $this->filter_delimiter;
            }
        }

        return $filterHash;
    }

    /**
     * Set the filter state to dirty.
     */
    public function setFiltersStateDirty()
    {
        $this->filtersState = self::FILTERS_STATE_DIRTY;
    }

    /**
     * Clears the collection, removing all elements.
     *
     * @return void
     */
    function clear()
    {
        $this->_elements = new ArrayCollection();
    }

    /**
     * Checks whether an element is contained in the collection.
     * This is an O(n) operation, where n is the size of the collection.
     *
     * @param ApiFilter $element The element to search for.
     *
     * @return boolean TRUE if the collection contains the element, FALSE otherwise.
     */
    function contains(ApiFilter $element)
    {
        return $this->_elements->contains($element);
    }

    /**
     * Checks whether the collection is empty (contains no elements).
     *
     * @return boolean TRUE if the collection is empty, FALSE otherwise.
     */
    function isEmpty()
    {
        return $this->_elements->isEmpty();
    }

    /**
     * Removes the element at the specified index from the collection.
     *
     * @param string|integer $key The kex/index of the element to remove.
     *
     * @return ApiFilter The removed element or NULL, if the collection did not contain the element.
     */
    function remove($key)
    {
        return $this->_elements->remove($key);
    }

    /**
     * Checks whether the collection contains an element with the specified key/index.
     *
     * @param string|integer $key The key/index to check for.
     *
     * @return boolean TRUE if the collection contains an element with the specified key/index,
     *                 FALSE otherwise.
     */
    function containsKey($key)
    {
        return $this->_elements->containsKey($key);
    }

    /**
     * Gets the element at the specified key/index.
     *
     * @param string|integer $key The key/index of the element to retrieve.
     *
     * @return ApiFilter
     */
    function get($key)
    {
        return $this->_elements->get($key);
    }

    /**
     * Sets an element in the collection at the specified key/index.
     *
     * @param ApiFilter $value The element to set.
     *
     * @return void
     */
    function set(ApiFilter $value)
    {
        $this->_elements->set($value->getName(), $value);
    }
    /**
     * Add an element in the collection at the specified key/index.
     *
     * @param ApiFilter $value The element to set.
     *
     * @return void
     */
    public function add(ApiFilter $value)
    {
        $this->set($value);
    }
    /**
     * Gets a native PHP array representation of the collection.
     *
     * @return array
     */
    function toArray()
    {
        return $this->_elements;
    }

    /**
     * Sets the internal iterator to the first element in the collection and returns this element.
     *
     * @return mixed
     */
    function first()
    {
        return $this->_elements->first();
    }

    /**
     * Sets the internal iterator to the last element in the collection and returns this element.
     *
     * @return Filter
     */
    function last()
    {
        return $this->_elements->last();
    }

    /**
     * Gets the key/index of the element at the current iterator position.
     *
     * @return int|string
     */
    function key()
    {
        return $this->_elements->key();
    }

    /**
     * Gets the element of the collection at the current iterator position.
     *
     * @return mixed
     */
    function current()
    {
        return $this->_elements->current();
    }

    /**
     * Moves the internal iterator position to the next element and returns this element.
     *
     * @return mixed
     */
    function next()
    {
        return $this->_elements->next();
    }

    /**
     *
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        //$this->current()->getName() == $this->key()->getName();
        return true;
    }
    /**
     *
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        return $this->_elements->first();
    }
    /**
     * Gets the index/key of a given element. The comparison of two elements is strict,
     * that means not only the value but also the type must match.
     * For objects this means reference equality.
     *
     * @param ApiFilter $element The element to search for.
     *
     * @return int|string|bool The key/index of the element or FALSE if the element was not found.
     */
    function indexOf(ApiFilter $element)
    {
        return $this->_elements->indexOf($element);
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
        return new \ArrayIterator($this->_elements->toArray());
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return $this->_elements->count();
    }
}