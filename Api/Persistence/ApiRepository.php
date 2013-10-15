<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Persistence;

use Ant\ChateaClient\Client\Api;
use Ant\Bundle\ChateaClientBundle\Api\Util\Pager;

abstract class ApiRepository extends Api implements ObjectRepository
{

     protected $_entityName;
     protected $_manager;
     protected $_class;
     private $pager;

    public function __construct(ApiManager $manager, $className)
    {
          parent::__construct($manager->getConnection());

          $this->_entityName = $className;
          $this->_manager = $manager;
          $this->_class = new $className();
          $className::setRepository( $this );

    }
    /**
          * @return string
          */
     protected function getEntityName()
     {
         return $this->_entityName;
     }

     /**
      * @return ApiManager
      */
     protected function getApiManager()
     {
         return $this->_manager;
     }

    /**
     *
     */
    protected function getClass()
    {
        return $this->_class;
    }

    public function getPager()
    {
        if (null === $this->pager) {
            $this->pager = new Pager($this);
        }
        return $this->pager;
    }
    public abstract function hydrate(array $item);

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
    public abstract function save(&$object);

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
    public abstract function update(&$object);

    /**
     * Removes an object instance.
     *
     * A removed object will be removed from the server.
     *
     * @param number $object_id The object instance to remove.
     *
     * @return void
     */
    public abstract function delete($object_id);

}