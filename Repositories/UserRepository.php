<?php

namespace Ant\Bundle\ChateaClientBundle\Repositories;
use Ant\Common\Collections\ArrayCollection;
use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiRepository;
use Ant\Bundle\ChateaClientBundle\Api\Model\Channel;
use Ant\Bundle\ChateaClientBundle\Api\Model\UserProfile;
use Ant\Bundle\ChateaClientBundle\Api\Model\User;

class UserRepository  extends  ApiRepository
{

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string
     */
    public function getClassName()
    {
        return "Ant\\Bundle\\ChateaClientBundle\\Repositories\\UserRepository";
    }
    public function hydrate(array $item = null)
    {
        if($item == null){
            return new User();
        }
        $id             = array_key_exists('id',$item)?$item['id']:0;
        $username       = array_key_exists('username',$item)?$item['username']:'not-username';
        $email          = array_key_exists('email',$item)?$item['email']:true;
        $enabled        = array_key_exists('enabled',$item)?$item['enabled']:true;
        $last_login     = array_key_exists('last_login',$item)?$item['last_login']: new \DateTime('now');

        return new User($id,$username,$email,$last_login,$enabled);
    }

    public function findById($id)
    {
        if($id === null || $id === 0 && !$id)
        {
            return null;
        }

        return $this->hydrate($this->showUser($id));
    }

    public function findAll($limit =0, $offset = 30)
    {
        $array_data = $this->who();

        $data = array_key_exists('resources',$array_data)?$array_data['resources']: array();
        $collection = new ApiCollection($array_data['total'],$array_data['page'],$array_data['limit']);

        foreach($data as $item ){

            $collection->add($this->hydrate($item));
        }
        return $collection;
    }

    public function findMeUser()
    {

        return $this->hydrate($this->whoami());
    }
    public function findProfile($user_id)
    {
        $userProfileRepository = $this->_manager->getRepository(get_class(new UserProfile()));

        return $userProfileRepository->findByUserId($user_id);
    }
    public function save(&$object)
    {
        throw new \Exception("this method is not avaliable");
    }

    public function update(&$object)
    {
        if(!($object instanceof User)){
            throw new \InvalidArgumentException('The parameter have been of type User');
        }

        $this->updateUser($object->getUsername(), $object->getEmail(),$object->getPassword());
    }

    public function delete($object)
    {
        throw new \Exception("this method is not avaliable");
    }
}