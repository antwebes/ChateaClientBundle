<?php
 /*
 * This file (CountryManager.php) is part of the socialChatea project.
 *
 * 2014 (c) Ant-Web S.L.  
 * Created by Javier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 10/01/14 - 9:05
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

namespace Ant\Bundle\ChateaClientBundle\Manager;
use Ant\Bundle\ChateaClientBundle\Api\Model\Country;

/**
 * Class CountryManager
 * @package Ant\Bundle\ChateaClientBundle\Manager
 */
class CountryManager extends BaseManager
{
    protected $limit;

    public function hydrate(array $item = null)
    {
        $country = new Country();
        $country->setId(array_key_exists('id',$item)?$item['id']:null);
        $country->setName(array_key_exists('name',$item)?$item['name']:'not-name');
        $country->setCode(array_key_exists('country_code',$item)?$item['country_code']:'');
        return $country;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function findById($id)
    {
        throw new \InvalidArgumentException("This method do not supported yet");
    }

    public function findByCode($countryCode)
    {
        return $this->executeAndHydrateOrHandleApiException('getCountry', array('country_code' => $countryCode));
    }

    public function findAll($page = 1, array $filters = null, $limit = null, array $order = null)
    {
        //return $this->hydrate($this->getManager()->showAffiliates());
    }
    public function save(&$object)
    {
    }
    public function update(&$object)
    {
        throw new \InvalidArgumentException("This method do not supported yet");
    }

    public function delete($object_id)
    {
        throw new \InvalidArgumentException("This method do not supported yet");
    }

    public function getModel()
    {
        return 'Ant\Bundle\ChateaClientBundle\Api\Model\Country';
    }
} 