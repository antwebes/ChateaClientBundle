<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Model\OutstandingEntry;
use Ant\Bundle\ChateaClientBundle\Api\Util\Pager;
use Ant\Bundle\ChateaClientBundle\Api\Util\Command;

/**
 * Class OutstandingEntryManager
 * @package Ant\Bundle\ChateaAdminBundle\Manager
 */
class OutstandingEntryManager extends BaseManager
{
    protected $limit;

    public function hydrate(array $item = null)
    {
        $outstandingEntry = new OutstandingEntry();

        $outstandingEntry->setManager($this);
        $outstandingEntry->setId(array_key_exists('id',$item)?$item['id'] : null);
        $outstandingEntry->setInvalidated(array_key_exists('invalidated',$item)?$item['invalidated'] : false);
        $outstandingEntry->setUntil(array_key_exists('until',$item) ? new \DateTime($item['until']) : null);
        $outstandingEntry->setFrom(array_key_exists('from',$item) ? new \DateTime($item['from']) : null);
        $outstandingEntry->setCreatedAt(array_key_exists('created_at',$item) ? new \DateTime($item['created_at']) : null);

        if(isset($item['outstander']) && isset($item['outstander']['id'])){
            $outstander = $this->get('UserManager')->hydrate($item['outstander']);
            $outstandingEntry->setOutstander($outstander);
        }

        if(isset($item['resource']) && isset($item['resource']['id'])){
            $resource = $this->hydrateResource($item);
            $outstandingEntry->setResource($resource);
        }

        return $outstandingEntry;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function findById($id)
    {
        throw new \Exception('This function is not enabled yet');
    }

    public function findAll($page = 1, array $filters = null, $limit = null, array $order = null)
    {
        if (!$limit) {
            $limit = $this->limit;
        }

        $command = new Command('findOperators',array('filter' => $filters, 'order' => $order));
        return new Pager($this,$command, $page, $limit);
    }

    public function findOperatorsType()
    {
        return $this->getManager()->findOperatorsType();
    }
    public function save(&$object)
    {
        if(!($object instanceof Operator)){
            throw new \InvalidArgumentException('The parameter have been of type Operator');
        }

        $newOperator = $this->getManager()->saveOperators(
            $object->getUsername(),
            $object->getPassword(),
            $object->getType(),
            $object->getHostname()
        );
        $object = $this->hydrate($newOperator);
    }

    /**
     * @param $object
     * @throws \Exception This function is not enabled yet
     */
    public function update(&$object)
    {
        throw new \Exception('This function is not enabled yet');
    }

    /**
     * @param $object_id
     */
    public function delete($object_id)
    {
        $this->getManager()->deleteOperators($object_id);
    }

    public function getModel()
    {
        return 'Ant\Bundle\ChateaClientBundle\Api\Model\OutstandingEntry';
    }

    /**
     * @param array $item
     * @return mixed
     */
    private function hydrateResource(array $item)
    {
        if($item['resource_type'] == 'User'){
            $manager = $this->get('UserManager');
        }else{
            $manager = $this->get('ChannelManager');
        }

        $resource = $manager->hydrate($item['resource']);
        return $resource;
    }
}