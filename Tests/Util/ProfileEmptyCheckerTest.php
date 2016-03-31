<?php
/**
 * User: José Ramón Fernandez Leis
 * Email: jdeveloper.inxenio@gmail.com
 * Date: 13/01/16
 * Time: 10:23
 */

namespace Ant\Bundle\ChateaClientBundle\Tests\Util;

use Ant\Bundle\ChateaClientBundle\Util\ProfileEmptyChecker;
use Ant\Bundle\ChateaClientBundle\Api\Model\UserProfile;

class ProfileEmptyCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getCheckProperties
     */
    public function testIsProfileEmptyWithFilledProperties($validProperties)
    {
        $profileEmptyChecker = new ProfileEmptyChecker($validProperties);
        $profile = new UserProfile(0, 'about',null, 0, 'male', 'seeking', 'youWant', null, null);

        $this->assertFalse($profileEmptyChecker->isProfileEmpty($profile));
    }

    /**
     * @dataProvider getCheckProperties
     */
    public function testIsProfileEmptyWithNonFilledProperties($validProperties)
    {
        $profileEmptyChecker = new ProfileEmptyChecker($validProperties);
        $profile = new UserProfile(0, '',null, 0, '', '', '', null, null);

        $this->assertTrue($profileEmptyChecker->isProfileEmpty($profile));
    }

    public function testIsProfileEmptyWithNonExistingProperty()
    {
        $profileEmptyChecker = new ProfileEmptyChecker(array('thisisanonexistingproperty'));
        $profile = new UserProfile(0, 'about',null, 0, 'male', 'seeking', 'youWant', null, null);

        $this->assertFalse($profileEmptyChecker->isProfileEmpty($profile));
    }

    public function getCheckProperties()
    {
        return array(
            array(array('gender')),
            array(array('gender', 'youWant')),
            array(array('gender', 'youWant', 'about')),
            array(array('gender', 'youWant', 'about', 'seeking'))
        );
    }
}