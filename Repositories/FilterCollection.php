<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ant3
 * Date: 2/10/13
 * Time: 17:02
 * To change this template use File | Settings | File Templates.
 */

namespace Ant\Bundle\ChateaClientBundle\Repositories;

use Closure;
use Traversable;

class FilterCollection implements Countable, IteratorAggregate, ArrayAccess
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
     * An array containing the entries of this collection.
     *
     * @var array
     */
    private $_elements;

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
     *
     * @throws \InvalidArgumentException If the filter does not exist.
     *
     * @return Filter The enabled filter.
     */
    public function enable($name)
    {
        if (null === $filterClass = $this->get($name)) {
            throw new \InvalidArgumentException("Filter '" . $name . "' does not exist.");
        }

        if (!isset($this->enabledFilters[$name])) {
            $this->enabledFilters[$name] = $this->get($name);

            // Keep the enabled filters sorted for the hash
            ksort($this->enabledFilters);

            // Now the filter collection is dirty
            //$this->filtersState = self::FILTERS_STATE_DIRTY;
        }

        return $this->enabledFilters[$name];
    }
    /**
     * Disables a filter.
     *
     * @param string $name Name of the filter.
     *
     * @return Filter The disabled filter.
     *
     * @throws \InvalidArgumentException If the filter does not exist.
     */
    public function disable($name)
    {
        // Get the filter to return it
        $filter = $this->get($name);

        unset($this->enabledFilters[$name]);

        // Now the filter collection is dirty ?
        //$this->filtersState = self::FILTERS_STATE_DIRTY;

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
        if (self::FILTERS_STATE_CLEAN === $this->filtersState) {
            return $this->filterHash;
        }

        $filterHash = '';
        foreach ($this->enabledFilters as $name => $filter) {
            $filterHash .= '?filter='.$name .'='. $filter->getValue();
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
     * Adds an element at the end of the collection.
     *
     * @param mixed $element The element to add.
     *
     * @return boolean Always TRUE.
     */
    function add(Filter $element)
    {

        $this->_elements[$element->getName()] = $element;
    }

    /**
     * Clears the collection, removing all elements.
     *
     * @return void
     */
    function clear()
    {
        $this->_elements = array();
    }

    /**
     * Checks whether an element is contained in the collection.
     * This is an O(n) operation, where n is the size of the collection.
     *
     * @param mixed $element The element to search for.
     *
     * @return boolean TRUE if the collection contains the element, FALSE otherwise.
     */
    function contains(Filter $element)
    {
        return in_array($element, $this->_elements, true);
    }

    /**
     * Checks whether the collection is empty (contains no elements).
     *
     * @return boolean TRUE if the collection is empty, FALSE otherwise.
     */
    function isEmpty()
    {
        return ! $this->_elements;
    }

    /**
     * Removes the element at the specified index from the collection.
     *
     * @param string|integer $key The kex/index of the element to remove.
     *
     * @return mixed The removed element or NULL, if the collection did not contain the element.
     */
    function remove($key)
    {
        if (isset($this->_elements[$key]) || array_key_exists($key, $this->_elements)) {
            $removed = $this->_elements[$key];
            unset($this->_elements[$key]);

            return $removed;
        }

        return null;
    }

    /**
     * Removes the specified element from the collection, if it is found.
     *
     * @param mixed $element The element to remove.
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    function removeElement(Filter $element)
    {
        if (array_key_exists($element->getName(),$this->_elements)) {
            unset($this->_elements[$element->getName()]);

            return true;
        }

        return false;
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
        return isset($this->_elements[$key]) || array_key_exists($key, $this->_elements);
    }

    /**
     * Gets the element at the specified key/index.
     *
     * @param string|integer $key The key/index of the element to retrieve.
     *
     * @return mixed
     */
    function get($key)
    {
        if (isset($this->_elements[$key])) {
            return $this->_elements[$key];
        }
        return null;
    }

    /**
     * Gets all keys/indices of the collection.
     *
     * @return array The keys/indices of the collection, in the order of the corresponding
     *               elements in the collection.
     */
    function getKeys()
    {
        return array_keys($this->_elements);
    }

    /**
     * Gets all values of the collection.
     *
     * @return array The values of all elements in the collection, in the order they
     *               appear in the collection.
     */
    function getValues()
    {
        return array_values($this->_elements);
    }

    /**
     * Sets an element in the collection at the specified key/index.
     *
     * @param string|integer $key   The key/index of the element to set.
     * @param mixed $value The element to set.
     *
     * @return void
     */
    function set($key, Filter $value)
    {
        $this->_elements[$key] = $value;
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
        return reset($this->_elements);
    }

    /**
     * Sets the internal iterator to the last element in the collection and returns this element.
     *
     * @return mixed
     */
    function last()
    {
        return end($this->_elements);
    }

    /**
     * Gets the key/index of the element at the current iterator position.
     *
     * @return int|string
     */
    function key()
    {
        return key($this->_elements);
    }

    /**
     * Gets the element of the collection at the current iterator position.
     *
     * @return mixed
     */
    function current()
    {
        return current($this->_elements);
    }

    /**
     * Moves the internal iterator position to the next element and returns this element.
     *
     * @return mixed
     */
    function next()
    {
        return next($this->_elements);
    }

    /**
     * Tests for the existence of an element that satisfies the given predicate.
     *
     * @param Closure $p The predicate.
     *
     * @return boolean TRUE if the predicate is TRUE for at least one element, FALSE otherwise.
     */
    function exists(Closure $p)
    {
        foreach ($this->_elements as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns all the elements of this collection that satisfy the predicate p.
     * The order of the elements is preserved.
     *
     * @param Closure $p The predicate used for filtering.
     *
     * @return Collection A collection with the results of the filter operation.
     */
    function filter(Closure $p)
    {
        return new static(array_filter($this->_elements, $p));
    }

    /**
     * Applies the given predicate p to all elements of this collection,
     * returning true, if the predicate yields true for all elements.
     *
     * @param Closure $p The predicate.
     *
     * @return boolean TRUE, if the predicate yields TRUE for all elements, FALSE otherwise.
     */
    function forAll(Closure $p)
    {
        foreach ($this->_elements as $key => $element) {
            if ( ! $p($key, $element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Applies the given function to each element in the collection and returns
     * a new collection with the elements returned by the function.
     *
     * @param Closure $func
     *
     * @return Collection
     */
    function map(Closure $func)
    {
        return new static(array_map($func, $this->_elements));
    }

    /**
     * Partitions this collection in two collections according to a predicate.
     * Keys are preserved in the resulting collections.
     *
     * @param Closure $p The predicate on which to partition.
     *
     * @return array An array with two elements. The first element contains the collection
     *               of elements where the predicate returned TRUE, the second element
     *               contains the collection of elements where the predicate returned FALSE.
     */
    function partition(Closure $p)
    {
        $coll1 = $coll2 = array();
        foreach ($this->_elements as $key => $element) {
            if ($p($key, $element)) {
                $coll1[$key] = $element;
            } else {
                $coll2[$key] = $element;
            }
        }
        return array(new static($coll1), new static($coll2));
    }

    /**
     * Gets the index/key of a given element. The comparison of two elements is strict,
     * that means not only the value but also the type must match.
     * For objects this means reference equality.
     *
     * @param mixed $element The element to search for.
     *
     * @return int|string|bool The key/index of the element or FALSE if the element was not found.
     */
    function indexOf($element)
    {
        return array_search($element, $this->_elements, true);
    }

    /**
     * Extracts a slice of $length elements starting at position $offset from the Collection.
     *
     * If $length is null it returns all elements from $offset to the end of the Collection.
     * Keys have to be preserved by this method. Calling this method will only return the
     * selected slice and NOT change the elements contained in the collection slice is called on.
     *
     * @param int $offset The offset to start from.
     * @param int|null $length The maximum number of elements to return, or null for no limit.
     *
     * @return array
     */
    function slice($offset, $length = null)
    {
        return array_slice($this->_elements, $offset, $length, true);
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
        return new \ArrayIterator($this->_elements);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, Filter $value)
    {
        if ( ! isset($offset)) {
            return $this->add($value);
        }
        return $this->set($offset, $value);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
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
        return count($this->_elements);
    }
}