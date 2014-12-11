<?php
 /*
 * This file (AffiliateManager.php) is part of the adminchatea project.
 *
 * 2013 (c) Ant-Web S.L.  
 * Created by Javier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 20/12/13 - 12:23
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Model\Affiliate;

/**
 * Class AffiliateManager
 * @package Ant\Bundle\ChateaClientBundle\Manager
 */
class AffiliateManager extends BaseManager
{
    protected $limit;

    public function hydrate(array $item = null)
    {
        $affiliate = new Affiliate();
        $affiliate->setName(array_key_exists('name',$item)?$item['name']:null);
        $affiliate->setHost(array_key_exists('host',$item)?$item['host']:null);
        $affiliate->setConfirmedUri(array_key_exists('confirmed_uri',$item)?$item['confirmed_uri']:null);
        $affiliate->setResettingUrl(array_key_exists('resetting_url',$item)?$item['resetting_url']:null);
        return $affiliate;
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

    public function findAll($page = 1, array $filters = null, $limit = null, array $order = null)
    {
        //return $this->hydrate($this->getManager()->showAffiliates());
    }

    public function save(&$object)
    {
        throw new \InvalidArgumentException("This method do not supported yet");
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
        return 'Ant\Bundle\ChateaClientBundle\Api\Model\Affiliate';
    }
} 