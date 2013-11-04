<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;
use Ant\Bundle\ChateaClientBundle\Api\Util\Pager;
use Ant\Bundle\ChateaClientBundle\Api\Model\UserProfile;
use Ant\ChateaClient\Client\ApiException;

class UserProfileManager extends  BaseManager implements ManagerInterface
{

    public function hydrate(array $item = null)
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
    public function getModel()
    {
        return 'Ant\Bundle\ChateaClientBundle\Api\Model\UserProfile';
    }

    public function findAll($page = 1, array $filters = null, $limit= null)
    {
        throw new \Exception("This metod not soported in server yet");
    }

    public function findById($id)
    {
        if($id === null || $id === 0 && !$id)
        {
            return null;
        }
        $profile = new UserProfile();
        try{
            $profile = $this->hydrate($this->getManager()->showUserProfile($id));
        }catch (ApiException $ex){
        }

        return $profile;
    }

    public function save(&$object)
    {

        if(!($object instanceof UserProfile)){
            throw new \InvalidArgumentException("The object has been of type ");
        }
        return $this->getManager()->addUserProfile($object->getId(),$object->getAbout(),$object->getSexualOrientation());
    }

    public function update(&$object)
    {
        throw new \Exception("This metod not soported in server yet");
    }

    public function delete($object)
    {
        throw new \Exception("This metod not soported in server yet");
    }

    public function getLimit()
    {
        return $this->limit;
    }
}