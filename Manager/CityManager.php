<?php
 /*
 * This file (CityManager.php) is part of the socialChatea project.
 *
 * 2014 (c) Ant-Web S.L.  
 * Created by Javier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 13/01/14 - 16:42
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Model\City;

class CityManager extends BaseManager
{
    protected $limit;

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }
    public function getLimit()
    {
        return $this->limit;
    }

    public function hydrate(array $item = null, City $city = null)
    {
        if($item == null){
            return null;
        }

        if($city == null){
            $city = new City();
        }
        
        $city->setId(array_key_exists('id',$item)?$item['id']:0);
        $city->setName(array_key_exists('name',$item)?$item['name']:'not-name');
        $city->setCountry(array_key_exists('country',$item)?$item['country']['name']:'not-country');
        
        return $city;
    }

    public function findById($id)
    {
    	if($id === null || $id === 0 || !$id){
    		return null;
    	}

        return $this->executeAndHydrateOrHandleApiException('getCity', array($id));
    }

    public function findAll($page = 1, array $filters = null, $limit = null, array $order = null)
    {
        // TODO: Implement findAll() method.
    }

    public function save(&$object)
    {
        // TODO: Implement save() method.
    }

    public function update(&$object)
    {
        // TODO: Implement update() method.
    }

    public function delete($object_id)
    {
        // TODO: Implement delete() method.
    }

    public function getModel()
    {
        return 'Ant\Bundle\ChateaClientBundle\Api\Model\City';
    }
}