<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;
use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;
use Ant\Bundle\ChateaClientBundle\Api\Util\Command;
use Ant\Bundle\ChateaClientBundle\Api\Util\Pager;
use Ant\Bundle\ChateaClientBundle\Api\Model\Channel;
use Ant\Bundle\ChateaClientBundle\Api\Model\UserProfile;
use Ant\Bundle\ChateaClientBundle\Api\Model\User;

class UserManager extends BaseManager implements ManagerInterface
{
    private $limit;
    private $meUser;

    public function __construct(ApiManager $apiManager, $limit)
    {
        parent::__construct($apiManager);
        User::setManager($this);
        $this->limit = $limit;
        $this->meUser  = $this->hydrate($this->getManager()->whoami());
    }

	static public function hydrate(array $item = null)
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

        return $this->hydrate($this->getManager()->showUser($id));
    }

    public function findAll($page = 1, array $filters = null, $limit= null)
    {
        if($limit == null){
            $limit = $this->limit;
        }
        $commnad = new Command('who',array('filters'=>$filters));
        return  new Pager($this->getManager(),$commnad, $page, $limit, $filters);

    }
    public function findBlockedUsers($user_id,$page = 1, $limit= null)
    {
        if($limit == null){
            $limit = $this->limit;
        }
        $commnad = new Command('showUsersBlocked',array('user'=>$user_id));

        return  new Pager($this->getManager(),$commnad ,$page, $limit);
    }
    public function findMeBlocked($page = 1, $limit= null)
    {
        return  $this->findBlockedUsers($this->meUser->getId(),$page,$limit);
    }

    public function bockedUser(User $user_blocked)
    {

        return $this->getManager()->addUserBlocked($this->meUser->getId(),$user_blocked->getId());
    }

    public function findMeUser()
    {
        return $this->meUser;
    }

    public function findProfile($user_id)
    {
        $profile = $this->getManager()->showUserProfile($user_id);
        UserProfileManager::hydrate($profile);
    }

    public function findMeProfile()
    {
        return $this->findProfile($this->meUser->getId());
    }

    public function updateProfile(UserProfile $profile)
    {
        $profile = $this->getManager()->updateUserProfile($this->meUser->getId(),$profile->getAbout(),$profile->getSexualOrientation());
        return UserProfileManager::hydrate($profile);
    }
    public function findChannlesCreatedByUserId($user_id)
    {
        $commnad = new Command('showUsersBshowUserChannelslocked',array('user_id'=>$user_id));
        return  new Pager($this->getManager(),$commnad ,1, $this->limit);

    }
    public function finChannelsCreatedByMe()
    {
        return $this->findChannlesCreatedByUserId($this->meUser->getId());
    }

    public function findPhotos($user_id, $page= 1, $limit = null)
    {
        if($limit == null){
            $limit = $this->limit;
        }

        $commnad = new Command('showPhotos',array('user_id'=>$user_id));
        return  new Pager($this->getManager(),$commnad ,$page, $limit);


    }
    public function findVisit($user_id)
    {
        throw new \Exception("Sen implementar");
    }
    public function findFriends($user_id, $page= 1, $limit = null)
    {
        if($limit == null){
            $limit = $this->limit;
        }

        $commnad = new Command('showFriends',array('user_id'=>$user_id));
        return  new Pager($this->getManager(),$commnad ,$page, $limit);
    }
    public function findMeFriends($page= 1, $limit = null)
    {
        return $this->findFriends($this->meUser->getId(),$page,$limit);
    }
    /**
     * @param \Ant\Bundle\ChateaClientBundle\Api\Model\User $object
     * @return array
     */
    public function save(&$object)
    {
        return $this->getManager()->register($object->getUsername(),$object->getEmail(),$object->getPassword(),$object->getPassword(),"webchatea.local");
    }

    public function update(&$object)
    {
        if(!($object instanceof User)){
            throw new \InvalidArgumentException('The parameter have been of type User');
        }

        $this->getManager()->updateUser($object->getUsername(), $object->getEmail(),$object->getPassword());
    }

    /**
     * @param \Ant\Bundle\ChateaClientBundle\Api\Model\User $object
     * @throws \InvalidArgumentException
     */
    public function delete($object)
    {
        if($object->getId() != $this->meUser->getId()){
            throw new \InvalidArgumentException("You only can delete your user");
        }

        $this->getManager()->delMe();
    }

    public function deleteMeUser()
    {
        $this->getManager()->delMe();
    }
}