<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Repository;


interface ObjectRepository
{
    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string
     */
    public function getClassName();

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param number $id The identifier.
     *
     * @return object The object.
     */
    public function find($id);

    /**
     * Finds all objects in the repository.
     *
     * @param int $limit
     * @param int $offset
     * @return \Ant\Common\Collections\Collection
     */
    public function findAll($limit =0, $offset = 30);


    /**
     * Tells the ObjectManager to make an instance managed and persistent.
     *
     * The object will be entered into the server.
     *
     *
     * @param object $object The instance to make managed and persistent.
     *
     * @return void
     */
    public function save(&$object);

    /**
     * Tells the ObjectManager to make an instance managed and updated.
     *
     * The object will be entered into the server.
     *
     *
     * @param object $object The instance to make managed and persistent.
     *
     * @return void
     */
    public function update(&$object);

    /**
     * Removes an object instance.
     *
     * A removed object will be removed from the server.
     *
     * @param number $object_id The object instance to remove.
     *
     * @return void
     */
    public function delete($object_id);

    /**
     * @param array $item
     * @return object The object instance to hydrate.
     */
    public function hydrate(array $item);
}