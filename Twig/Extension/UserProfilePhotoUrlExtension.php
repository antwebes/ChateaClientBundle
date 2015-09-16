<?php

namespace Ant\Bundle\ChateaClientBundle\Twig\Extension;

class UserProfilePhotoUrlExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('userProfilePhotoUrl', array($this, 'userProfilePhotoUrl')),
        );
    }

    public function getName()
    {
        return 'user_profile_photo_url';
    }
    //TODO remover extensión, posiblemente no haga falta
    public function userProfilePhotoUrl($user, $size)
    {
        if(($profilePhoto = $user->getProfilePhoto()) != null){
            return $this->getSizePath($profilePhoto, $size);
        } else {
            return "";
        }
    }

    private function getSizePath($profilePhoto, $size)
    {
        $getter = 'getPath'.ucfirst($size);

        if(method_exists($profilePhoto, $getter)){
            return $profilePhoto->$getter();
        }

        return '';
    }
}