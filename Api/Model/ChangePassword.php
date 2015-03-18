<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

/**
 * Class ChangePassword
 * @package Ant\Bundle\ChateaClientBundle\Api\Model
 */
class ChangePassword
{
    /**
     * @var string old password
     */
    private $currentPassword;

    /**
     * @var string new password
     */
    private $plainPassword;

    public function getCurrentPassword()
    {
        return $this->currentPassword;
    }

    /**
     * Set the current password
     *
     * @param string $currentPassword
     */
    public function setCurrentPassword($currentPassword)
    {
        $this->currentPassword = $currentPassword;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set the current password
     *
     * @param string $planPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }
}