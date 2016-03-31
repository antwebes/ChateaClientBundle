<?php
/**
 * User: José Ramón Fernandez Leis
 * Email: jdeveloper.inxenio@gmail.com
 * Date: 13/01/16
 * Time: 10:50
 */

namespace Ant\Bundle\ChateaClientBundle\Twig\Extension;

use Ant\Bundle\ChateaClientBundle\Util\ProfileEmptyChecker;
use Ant\Bundle\ChateaClientBundle\Api\Model\UserProfile;

class IsProfileEmptyExtension extends \Twig_Extension
{
    /**
     * @var ProfileEmptyChecker
     */
    private $profileEmptyChecker;

    public function __construct(ProfileEmptyChecker $profileEmptyChecker)
    {
        $this->profileEmptyChecker = $profileEmptyChecker;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('isProfileEmpty', array($this, 'isProfileEmpty')),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'is_profile_empty';
    }

    public function isProfileEmpty(UserProfile $userProfile)
    {
        return $this->profileEmptyChecker->isProfileEmpty($userProfile);
    }
}