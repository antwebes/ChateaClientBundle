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
        $city->setName(array_key_exists('name',$item)?$item['name'] : null);
        $city->setCountry(array_key_exists('country_name',$item)?$item['country_name'] : null);
        $city->setRegion(array_key_exists('region_name',$item)?$item['region_name'] : null);
        $city->setProvince(array_key_exists('province_name',$item)?$item['province_name'] : null);

        if(array_key_exists('city_es',$item)){
            $city->setName(array_key_exists('name',$item['city_es'])?$item['city_es']['name'] : null, 'es');
            $city->setCountry(array_key_exists('country_name',$item['city_es'])?$item['city_es']['country_name'] : null, 'es');
            $city->setRegion(array_key_exists('region_name',$item['city_es'])?$item['city_es']['region_name'] : null, 'es');
            $city->setProvince(array_key_exists('province_name',$item['city_es'])?$item['city_es']['province_name'] : null, 'es');
        }

        if(array_key_exists('country', $item)){
            $city->setCountryObject($this->hydrateCountry($item['country']));
        }

        return $city;
    }

    private function hydrateCountry($data)
    {
        return $this->get('CountryManager')->hydrate($data);
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