<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;
use Ant\Bundle\ChateaClientBundle\Api\Util\Command;
use Ant\Bundle\ChateaClientBundle\Api\Util\Pager;
use Ant\Bundle\ChateaClientBundle\Api\Util\Paginator;
use Ant\Bundle\ChateaClientBundle\Api\Model\Channel;
use Ant\Bundle\ChateaClientBundle\Api\Model\UserProfile;
use Ant\Bundle\ChateaClientBundle\Api\Model\User;

class UserManager extends BaseManager implements ManagerInterface
{
    private $limit;
    private $meUser;

	public function setLimit($limit)
	{
		$this->limit = $limit;
	}
	public function getLimit()
    {
        return $this->limit;
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

        $user = new User($id,$username,$email,$last_login,$enabled);

        if(isset($item['profile'])){
            $userProfile = $this->get('UserProfileManager')->hydrate($item['profile']);
            $user->setProfile($userProfile);
        }

        if(isset($item['channels'])){
            $user->setChannels($this->mapChannels($item['channels']));
        }

        if(isset($item['channels_fan'])){
            $user->setChannelsFan($this->mapChannels($item['channels_fan']));
        }

        if(isset($item['channels_moderated'])){
            $user->setChannelsModerated($this->mapChannels($item['channels_moderated']));
        }

        return $user;
    }

    private function mapChannels($channelsMap)
    {
        $channelManager = $this->get('ChannelManager');

        $channelMapper = function($map) use ($channelManager){
            return $channelManager->hydrate($map);
        };

        return array_map($channelMapper, $channelsMap);
    }
    
	public function getModel()
	{
		return 'Ant\Bundle\ChateaClientBundle\Api\Model\User';	
	}
	
    public function findById($id)
    {
        if($id === null || $id === 0 && !$id)
        {
            return null;
        }

        return $this->hydrate($this->getManager()->showUser($id));
    }

    public function findAll($page = 1, array $filters = null, $limit= null, array $order = null)
    {
        $limit  = $limit == null ? $this->limit : $limit ;

        return  new Pager($this,new Command('showUsers',array()) ,$page, $limit);

    }
    public function findBlockedUsers($user_id,$page = 1, $limit= null)
    {
        $limit  = $limit == null ? $this->limit : $limit ;

        return  new Pager($this,new Command('showUsersBlocked',array('user_id'=>$user_id)) ,$page, $limit);

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
        return $this->hydrate($this->getManager()->me());

    }

    public function findProfile($user_id)
    {
        return $this->get('UserProfileManager')->findById($user_id);
    }

    public function updateProfile(UserProfile $profile)
    {
        /*
        $profile = $this->getManager()->updateUserProfile($this->meUser->getId(),$profile->getAbout(),$profile->getSexualOrientation());
        return UserProfileManager::hydrate($profile);
        */

    }
    public function findPhotos($user_id, $page= 1, $limit = null)
    {
        $limit  = $limit == null ? $this->limit : $limit ;
        return  new Pager($this,new Command('showPhotos',array('user_id'=>$user_id)) ,$page, $limit);

    }
    public function findVisit($user_id)
    {
        throw new \Exception("Sen implementar");
    }
    public function findFriends($user_id, $page =1, $limit = null)
    {
        $limit  = $limit == null ? $this->limit : $limit ;

        return  new Pager($this,new Command('showFriends',array('user_id'=>$user_id)) ,$page, $limit);
    }
    public function findMeFriends($page= 1, $limit = null)
    {
        return $this->findFriends($this->meUser->getId(),$page,$limit);
    }
    public function findFavoriteChannels($user_id, $page =1, $limit = null)
    {
        $channelManager = $this->get('ChannelManager');
        $limit  = $limit == null ? $this->limit : $limit ;

        return  new Pager($channelManager,new Command('showUserChannelsFan',array('user_id'=>$user_id)) ,$page, $limit);

    }
    public function findChannelsCreated($user_id, $page =1, $limit = null)
    {
        $channelManager = $this->get('ChannelManager');
        $limit  = $limit == null ? $this->limit : $limit ;

        $command = new Command('showUserChannels',array('user_id'=>$user_id));

        return  new Pager($channelManager,$command ,$page, $limit);

    }
    public function finChannelsCreatedByMe()
    {
        return $this->findChannelsCreated($this->meUser->getId());
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

    public function forgotPassword($usernameOrEmail)
    {
        $this->getManager()->forgotPassword($usernameOrEmail);
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