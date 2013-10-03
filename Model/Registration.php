<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ant3
 * Date: 1/10/13
 * Time: 18:12
 * To change this template use File | Settings | File Templates.
 */

namespace Ant\Bundle\ChateaClientBundle\Model;

use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\True;
use Symfony\Component\Validator\Constraints\Valid;

class Registration
{
    /**
     * @Type(type="Ant\Bundle\ChateaClientBundle\Model\User")
     * @Valid()
     */
    protected $user;

    /**
     * @NotBlank()
     * @True()
     */
    protected $termsAccepted;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getTermsAccepted()
    {
        return $this->termsAccepted;
    }

    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = (Boolean) $termsAccepted;
    }
}