<?php

namespace Ant\Bundle\ChateaClientBundle\Repositories;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiRepository;
use Ant\Bundle\ChateaClientBundle\Model\UserProfile;

class UserProfileRepository extends  ApiRepository
{

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string
     */
    public function getClassName()
    {
        return "Ant\\Bundle\\ChateaClientBundle\\Repositories\\UserProfileRepository";
    }

    public function findById($profile_id)
    {
        throw new \Exception("This metod not soported in server yet");
    }
    public function findByUserId($user_id)
    {
        if($user_id === null || $user_id === 0 && !$user_id)
        {
            return null;
        }

        $json_decode = json_decode($this->showUserProfile($user_id),true);

        $user_profile = $this->hydrate($json_decode);

        return $user_profile;
    }
    public function find($id)
    {
        throw new \Exception("This metod not soported in server yet");
    }

    public function findAll($limit =0, $offset = 30)
    {
        throw new \Exception("This metod not  implemented yet");
    }

    public function save(&$object)
    {
        throw new \Exception("This metod not  implemented yet");
    }

    public function update(&$object)
    {
        throw new \Exception("This metod not  implemented yet");
    }

    public function delete($object)
    {
        throw new \Exception("This metod not  implemented yet");
    }


    public function hydrate(array $item)
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
}