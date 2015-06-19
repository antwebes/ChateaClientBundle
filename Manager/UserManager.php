<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;
use Ant\Bundle\ChateaClientBundle\Api\Model\ChangeEmail;
use Ant\Bundle\ChateaClientBundle\Api\Model\ChangePassword;
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
        $user->setUsernameCanonical(array_key_exists('username_canonical',$item)?$item['username_canonical']:'not-usernameCanonical');
        $user->setEmail(array_key_exists('email',$item)?$item['email']:'not-email');
        $user->setNick(array_key_exists('nick',$item)?$item['nick']:'not-nick');
        $user->setLastLogin(array_key_exists('last_login',$item)?$item['last_login']: null);
		$user->setEnabled(array_key_exists('enabled',$item)?$item['enabled']: null);

        if(isset($item['client'])){
            $client = $this->get('ClientManager')->hydrate($item['client']);
            $user->setClient($client);
        }
        
        if(isset($item['channels_fan'])){
            $user->setChannelsFan($this->mapChannels($item['channels_fan']));
        }

        if(isset($item['profile'])){
            $userProfile = $this->get('UserProfileManager')->hydrate($item['profile']);
            $user->setProfile($userProfile);
        }
        
        if(isset($item['channels'])){
        	$user->setChannels($this->mapChannels($item['channels']));
        }        

        if(isset($item['channels_moderated'])){
            $user->setChannelsModerated($this->mapChannels($item['channels_moderated']));
        }

        if(isset($item['city'])){
            $city = $this->get('CityManager')->hydrate($item['city']);
            $user->setCity($city);
        }
        if(isset($item['language'])){
            $user->setLanguage($item['language']);
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
	
    public function findById($id, $asArray = false)
    {
        if(empty($id)){
            return null;
        }

        $data = $this->getManager()->showUser($id);

        if(!$asArray){
            return $this->hydrate($data);
        }else{
            return $data;
        }
    }

    public function findAll($page = 1, array $filters = null, $limit= null, array $order = null)
    {
        $limit  = $limit == null ? $this->limit : $limit;

        if($filters != null){
            foreach($filters as $key => $value) {
                $params[] = sprintf("%s=%s", $key, $value);
            }
            $filters = implode(',', $params);
        }

        if($order != null){
            foreach ($order as $key => $value) {
                $orders[] = sprintf("%s=%s", $key, $value);
            }
            $order = implode(',',$orders);
        }

        $command = new Command('showUsers',array('filters' => $filters, 'order' => $order));

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

    public function updateProfile(User $user)
    {
        $profile = $user->getProfile();

        $birthday = $profile->getBirthday();

        if($birthday instanceof \DateTime){
            $birthday = $birthday->format('Y-m-d');
        }

        $profile = $this->getManager()->updateUserProfile(
            $user->getId(),
            $profile->getAbout(),
            $profile->getSeeking(),
            $profile->getGender(),
            $profile->getYouWant(),
            $birthday
            );

        return $this->get('UserProfileManager')->hydrate($profile);
    }

    public function findPhotos($user_id, $page= 1, $limit = null)
    {
        $limit        = $limit == null ? $this->limit : $limit ;
        $photoManager = $this->get('PhotoManager');

        return  new Pager($photoManager, new Command('ShowUserPhotos', array('user_id' => $user_id)) ,$page, $limit);

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
        $data = $this->getManager()->register($object->getUsername(),$object->getEmail(),$object->getPlainPassword(),
            $object->getPlainPassword(),
            $object->getClient()->getId(),
            $object->getIp(),
            $object->getCity()?$object->getCity()->getId():null,
            $object->getLanguage(),
            $object->getFacebookId(),
            $object->isEnabled()
        );
        return $this->hydrate($data, $object);
    }

    public function changePassword(ChangePassword $changePassword)
    {
        $this->getManager()->changePassword($changePassword->getCurrentPassword(), $changePassword->getPlainPassword(), $changePassword->getPlainPassword());
    }

    public function changeEmail(ChangeEmail $changeEmail)
    {
        $this->getManager()->updateMe($changeEmail->getEmail(), $changeEmail->getPassword());
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
        return $this->getManager()->forgotPassword($usernameOrEmail);
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

    public function searchUserByName($partial)
    {
        $collectionUsers = $this->getManager()->searchUserByName($partial);
        $collection = array();
        if($collectionUsers != null || !empty($collectionUsers)){
            foreach($collectionUsers as $user){
                $collection[] = $this->hydrate($user);
            }
        }

        return $collection;
    }

    /**
     * Find user with webcam, profile, sex ...
     * @return array|null Find user with webcam, profile, sex ...
     */
    public function getRealTimeMedia()
    {
        return $this->getManager()->getRealtimeMedia();
    }

    public function getChannelsModerated($user_id, $page =1, $limit = null)
    {
        $channelManager = $this->get('ChannelManager');
        $limit  = $limit == null ? $this->limit : $limit ;
        return  new Pager($channelManager,new Command('getChannelsModerated',array('user_id'=>$user_id)) ,$page, $limit);

    }

    public function addUserProfile(&$object, $image = null)
    {
        /*** @var \Ant\Bundle\ChateaClientBundle\Api\Model\UserProfile $profile  */
        $profile = $object->getProfile() ? $object->getProfile() : null;
        $data = $this->getManager()->addUserProfile(
            $object->getId(),
            $profile->getAbout(),
            $profile->getSeeking(),
            $profile->getGender(),
            $profile->getYouWant(),
            $profile->getBirthday(),
            $image
        );
        return $this->hydrate($data, $object);
    }

    public function getUserVisits($user, $limit)
    {
        $data = $this->getManager()->getVisitorsOfUser($user->getId(), $limit);

        $users = array();

        foreach($data['resources'] as $resource){
            $users[] = $this->hydrate($resource['participant_voyeur']);
        }

        return $users;
    }
}
