<?php
namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\True;

class Registration
{
    /**
     * @Type(type="Ant\Bundle\ChateaClientBundle\Api\Model\User")
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