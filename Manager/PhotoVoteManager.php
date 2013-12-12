<?php
 /*
 * This file (PhotoVoteManager.php) is part of the adminchatea project.
 *
 * 2013 (c) Ant-Web S.L.
 * Created by Javier Fernández Rodríguez <jjbier@gmail.com>
 * Date: 12/12/13 - 9:38
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Ant\Bundle\ChateaClientBundle\Manager;

use Ant\Bundle\ChateaClientBundle\Api\Model\PhotoVote;
use Exception;
/**
 * Class PhotoVoteManager
 * @package Ant\Bundle\ChateaClientBundle\Manager
 */
class PhotoVoteManager extends BaseManager
{

    protected $limit;

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getModel()
    {
        return 'Ant\Bundle\ChateaClientBundle\Api\Model\PhotoVote';
    }

    public function hydrate(array $item = null, PhotoVote $photoVote = null)
    {
        if($item == null){
            return null;
        }

        if($photoVote == null){
            $photoVote = new PhotoVote();
        }
        $photoVote->setScore(array_key_exists('score',$item)?$item['score']:0);
        $photoVote->setPublicatedAt(array_key_exists('publicated_at',$item)?$item['publicated_at']:null);

        if(isset($item['participant']) && isset($item['participant']['id'])){
            $participant = $this->get('UserManager')->hydrate($item['participant']);
            $photoVote->setParticipant($participant);
        }

        return $photoVote;
    }

    public function findById($id)
    {
        throw new Exception('This option is not enabled');
    }

    public function findAll($page = 1, array $filters = null, $limit = null, array $order = null)
    {
        throw new Exception('This option is not implement yet');
    }

    public function save(&$object)
    {
        throw new Exception('This option is not implement yet');
    }

    public function update(&$object)
    {
        throw new Exception('This option is not implement yet');
    }

    public function delete($object_id)
    {
        throw new Exception('This option is not implement yet');
    }
}