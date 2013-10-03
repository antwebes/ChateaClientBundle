<?php

namespace Ant\Bundle\ChateaClientBundle\Repositories;
use Ant\Common\Collections\ArrayCollection;
use Ant\Bundle\ChateaClientBundle\Api\Repository\ApiRepository;
use Ant\Bundle\ChateaClientBundle\Model\Channel;
use Ant\Bundle\ChateaClientBundle\Model\UserProfile;
use Ant\Bundle\ChateaClientBundle\Model\User;

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

    public function findById($id)
    {
        return $this->find($id);
    }

    public function find($id)
    {

        if($id === null || $id === 0 && !$id)
        {
            return null;
        }

        $json_decode = json_decode($this->showUser($id),true);

        $user = $this->hydrate($json_decode);
        return $user;
    }
    public function whoami()
    {
        return $this->findMeUser();
    }
    public function findMeUser()
    {
        $json_decode = json_decode(parent::whoami(),true);
        $user = $this->hydrate($json_decode);
        return $user;
    }
    public function findAll($limit =0, $offset = 30)
    {
        $json_decode = json_decode($this->showUsers(),true);
        $data = array_key_exists('resources',$json_decode)?$json_decode['resources']: array();
        $collection = new ArrayCollection();
        foreach($data as $item )
        {
            $user = $this->hydrate($item);
            $collection->add($user);
        }
        return $collection;
    }

    public function findProfile($user_id)
    {
        $userProfileRepository = $this->_manager->getRepository(get_class(new UserProfile()));
        $userProfile = $userProfileRepository->findByUserId($user_id);

        return $userProfile;
    }
    public function save(&$object)
    {
        throw new \Exception("This metod not  implemented yet");
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
        throw new \Exception("This metod not  implemented yet");
    }


    public function hydrate(array $item)
    {
        $id             = array_key_exists('id',$item)?$item['id']:0;
        $username       = array_key_exists('username',$item)?$item['username']:'not-username';
        $email          = array_key_exists('email',$item)?$item['email']:true;
        $enabled        = array_key_exists('enabled',$item)?$item['enabled']:true;
        $last_login     = array_key_exists('last_login',$item)?$item['last_login']: new \DateTime('now');
        return new User($id,$username,$email,$last_login,$enabled);
    }
}