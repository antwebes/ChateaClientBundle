<?php
 /*
 * This file (ClientManager.php) is part of the adminchatea project.
 *
 * 2013 (c) Ant-Web S.L.  
 * Created by Pablo Cancelo
 * Date: 13/01/15 - 11:34
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Model\Affiliate;
use Ant\Bundle\ChateaClientBundle\Api\Model\Client;

/**
 * Class ClientManager
 * @package Ant\Bundle\ChateaClientBundle\Manager
 */
class ClientManager extends BaseManager
{
    protected $limit;

	public function hydrate(array $item = null, Client $client = null)
    {
        if($item == null){
            return null;
        }
        
        if($client == null){
        	$client = new Client();
        }        
        
        $client->setId(array_key_exists('id',$item)?$item['id']:null);
        $client->setRandomId(array_key_exists('random_id',$item)?$item['random_id']:null);
        $client->setSecret(array_key_exists('secret',$item)?$item['secret']:null);
        $client->setRedirectUris(array_key_exists('redirect_uris',$item)?$item['redirect_uris']:null);
        $client->setAllowedGrantTypes(array_key_exists('allowed_grant_types',$item)?$item['allowed_grant_types']:null);
        $client->setName(array_key_exists('name',$item)?$item['name']:null);
        $client->setConfirmedUri(array_key_exists('confirmed_uri',$item)?$item['confirmed_uri']:null);
        $client->setResettingUrl(array_key_exists('resetting_url',$item)?$item['resetting_url']:null);

        if(isset($item['affiliate']) && isset($item['affiliate'])){
            $affiliate = $this->get('AffiliateManager')->hydrate($item['affiliate']);
            $client->setAffiliate($affiliate);
        }
        return  $client;
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
        return 'Ant\Bundle\ChateaClientBundle\Api\Model\Client';
    }
} 