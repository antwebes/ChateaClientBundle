<?php
/**
 * User: José Ramón Fernandez Leis
 * Email: jdeveloper.inxenio@gmail.com
 * Date: 13/01/16
 * Time: 10:32
 */

namespace Ant\Bundle\ChateaClientBundle\Util;

use Ant\Bundle\ChateaClientBundle\Api\Model\UserProfile;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ProfileEmptyChecker
{
    /**
     * @var array
     */
    private $propertiesToCheck;

    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    public function __construct(array $propertiesToCheck)
    {
        $this->propertiesToCheck = $propertiesToCheck;
        $this->propertyAccessor = new PropertyAccessor();
    }

    public function isProfileEmpty(UserProfile $userProfile)
    {
        foreach($this->propertiesToCheck as $property){
            try{
                $value = $this->propertyAccessor->getValue($userProfile, $property);

                if($value === null || empty($value)){
                    return true;
                }
            }catch(NoSuchPropertyException $e){
                // avoid breakin the flow
            }

        }

        return false;
    }
}