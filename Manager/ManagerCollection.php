<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Countable;
use InvalidArgumentException;
use IteratorAggregate;

class ManagerCollection implements Countable, IteratorAggregate
{
    private $elements = array();
    private $manager = null;
    function __construct(BaseManager $manager, array $elements = array())
    {
        $this->manager = $manager;

        foreach($elements as $item){
            array_push($this->elements,$this->manager->hydrate($item));
        }
    }


    /**
     * Adds an element at the end of the collection.
     *
     * @param mixed $element The element to add.
     *
     * @return int the new number of elements in the array.
     * @throws  InvalidArgumentException
     */
    function add($element)
    {
        if(get_class($element) !== $this->manager->getModel()){
            throw new InvalidArgumentException("This element is not compatible with this collection, it only support  ".$this->manager->getModel());
        }
        return array_push($this->elements,$this->manager->hydrate($element));
    }

    public function get($key)
    {
        if (isset($this->elements[$key])) {
            return $this->elements[$key];
        }
        return null;
    }

    /**
     * Clears the collection, removing all elements.
     *
     * @return void
     */
    function clear()
    {
        $this->elements = array();
    }

    /**
     * Checks whether an element is contained in the collection.
     * This is an O(n) operation, where n is the size of the collection.
     *
     * @param mixed $element The element to search for.
     *
     * @return boolean TRUE if the collection contains the element, FALSE otherwise.
     */
    function contains($element)
    {
        if(get_class($element) !== $this->manager->getModel()){
            return false;
        }
        return  array_search($element, $this->elements, true);
    }

    /**
     * Checks whether the collection is empty (contains no elements).
     *
     * @return boolean TRUE if the collection is empty, FALSE otherwise.
     */
    function isEmpty()
    {
        return !$this->elements;
    }


    /**
     * Removes the specified element from the collection, if it is found.
     *
     * @param mixed $element The element to remove.
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     *
     * @throws InvalidArgumentException
     */
    function removeElement($element)
    {
        if(get_class($element) !== $this->manager->getModel()){
            throw new InvalidArgumentException("This element is not compatible with this collection, it only support  ".$this->manager->getModel());
        }

        $key = array_search($element, $this->elements, true);

        if ($key !== false) {
            unset($this->elements[$key]);

            return true;
        }

        return false;
    }

    /**
     * Gets all keys/indices of the collection.
     *
     * @return array The keys/indices of the collection, in the order of the corresponding
     *               elements in the collection.
     */
    function getKeys()
    {
        return array_keys($this->elements);
    }

    /**
     * Gets all values of the collection.
     *
     * @return array The values of all elements in the collection, in the order they
     *               appear in the collection.
     */
    function getValues()
    {
        return array_values($this->elements);
    }

    /**
     * Gets a native PHP array representation of the collection.
     *
     * @return array
     */
    function toArray()
    {
        return $this->elements;
    }

    /**
     * Sets the internal iterator to the first element in the collection and returns this element.
     *
     * @return mixed
     */
    function first()
    {
        return reset($this->elements);
    }

    /**
     * Sets the internal iterator to the last element in the collection and returns this element.
     *
     * @return mixed
     */
    function last()
    {
        return end($this->elements);
    }

    /**
     * Gets the key/index of the element at the current iterator position.
     *
     * @return int|string
     */
    function key()
    {
        return key($this->elements);
    }

    /**
     * Gets the element of the collection at the current iterator position.
     *
     * @return mixed
     */
    function current()
    {
        return current($this->elements);
    }

    /**
     * Moves the internal iterator position to the next element and returns this element.
     *
     * @return mixed
     */
    function next()
    {
        return next($this->elements);
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
        return array_search($element, $this->elements, true);
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
        return array_slice($this->elements,$offset,$length, true);
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
        return new ArrayIterator($this->elements);
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
       return count($this->elements);
    }

    /**
     * Returns a string representation of this object.
     *
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . '@' . spl_object_hash($this);
    }
}