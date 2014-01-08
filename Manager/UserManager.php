<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;
use Ant\Bundle\ChateaClientBundle\Api\Util\Command;
use Ant\Bundle\ChateaClientBundle\Api\Util\Pager;
use Ant\Bundle\ChateaClientBundle\Api\Util\Paginator;
use Ant\Bundle\ChateaClientBundle\Api\Model\Channel;
use Ant\Bundle\ChateaClientBundle\Api\Model\UserProfile;
use Ant\Bundle\ChateaClientBundle\Api\Model\User;
use Ant\Bundle\ChateaClientBundle\Api\Model\City;

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
	public function hydrate(array $item = null, User $user = null)
    {
        if($item == null){
            return new User();
        }

        if($user == null){
            $user = new User();
        }
        
        $user->setId(array_key_exists('id',$item)?$item['id']:0);
        $user->setUsername(array_key_exists('username',$item)?$item['username']:'not-username');
        $user->setEmail(array_key_exists('email',$item)?$item['email']:'not-email');
        $user->setNick(array_key_exists('nick',$item)?$item['nick']:'not-nick');

        if(isset($item['affiliate'])){
            $affiliate = $this->get('AffiliateManager')->hydrate($item['affiliate']);
            $user->setAffiliate($affiliate);
        }
        if(isset($item['channels_fan'])){
            $user->setChannelsFan($this->mapChannels($item['channels_fan']));
        }

        if(isset($item['profile'])){
            $userProfile = $this->get('UserProfileManager')->hydrate($item['profile']);
            $user->setProfile($userProfile);
        }

        if(isset($item['channels_moderated'])){
            $user->setChannelsModerated($this->mapChannels($item['channels_moderated']));
        }

        if(isset($item['profile'])){
            $userProfile = $this->get('UserProfileManager')->hydrate($item['profile']);
            $user->setProfile($userProfile);
        }

        if(isset($item['city']) && isset($item['city']['name'])){
            $city = new City();

            $city->setName($item['city']['name']);
            $user->setCity($city);
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
        if(empty($id)){
            return null;
        }
        return $this->hydrate($this->getManager()->showUser($id));
    }

    public function findAll($page = 1, array $filters = null, $limit= null, array $order = null)
    {
        $limit  = $limit == null ? $this->limit : $limit;
        $params = array();

        if($filters != null){
            foreach($filters as $key => $value) {
                $params[] = sprintf("%s=%s", $key, $value);
            }
        }

        $command = new Command('showUsers',array('filters' => implode(',', $params), 'order' => $order));

        return  new Pager($this,$command,$page, $limit);

    }
    public function findAllUsers()
    {
        $command = new Command('showUsers',array());
        $array_data = $this->getManager()->execute($command);
        $array_data = array_key_exists('resources',$array_data)? $array_data['resources']: array();
        $output = array();
        foreach($array_data as $data){
            $output[] = $this->hydrate($data);
        }
        return $output;
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
        return $this->getManager()->register($object->getUsername(),$object->getEmail(),$object->getPassword(),
            $object->getPassword(),
            $object->getAffiliate()->getHost(),
            $object->getIp()
        );
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

    public function setUserNick($user_id, $nick)
    {
        $this->getManager()->setUserNick($user_id, $nick);
    }
}
