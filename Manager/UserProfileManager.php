<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;
use Ant\Bundle\ChateaClientBundle\Api\Util\Pager;
use Ant\Bundle\ChateaClientBundle\Model\UserProfile;

class UserProfileManager extends  BaseManager implements ManagerInterface
{

    static public function hydrate(array $item = null)
    {

        $id                     = array_key_exists('id',$item)?$item['id']:0;
        $about                  = array_key_exists('about',$item)?$item['about']:'not-about';
        $birthday               = array_key_exists('birthday',$item)?$item['birthday']:null;
        $count_visits           = array_key_exists('count_visits',$item)?$item['count_visits']: 0;
        $sexual_orientation     = array_key_exists('sexual_orientation',$item)?$item['sexual_orientation']:null;
        $gender                 = array_key_exists('gender',$item)?$item['gender']:null;
        $you_want               = array_key_exists('you_want',$item)?$item['you_want']: null;
        $updatedAt              = array_key_exists('updated_at',$item)?$item['updated_at']: new \DateTime('now');
        $publicated_at          = array_key_exists('publicated_at',$item)?$item['publicated_at']: new \DateTime('now');

        return new UserProfile($id, $about, $birthday,$count_visits,$gender,$sexual_orientation,$you_want,$updatedAt,$publicated_at);
    }

    /**
     * List all users
     *
     * @param int $page
     * @param array $filters
     * @param null $limit
     * @return Pager
     */
    public function findAll($page = 1, array $filters = null, $limit= null)
    {
        if($limit == null){
            $limit = 1;
        }
        return  new Pager($this->getManager(),'who', $page, $limit, $filters);
    }

    /**
     * Find User by ID
     *
     * @param $profile_id
     * @throws \Exception
     */
    public function findById($profile_id)
    {
        throw new \Exception("This metod not soported in server yet");
    }


    public function save(&$object)
    {
        throw new \Exception("This metod not soported in server yet");
    }

    public function update(&$object)
    {
        throw new \Exception("This metod not soported in server yet");
    }

    public function delete($object)
    {
        throw new \Exception("This metod not soported in server yet");
    }
}