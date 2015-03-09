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
    private $oldPassword;

    /**
     * @var string new password
     */
    private $newPassword;

    /**
     * @var string repeat new password
     */
    private $repeatPassword;

    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * Set the old password
     *
     * @param string $oldPassword
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }

    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * Set the new password
     *
     * @param string $newPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    public function getRepeatPassword()
    {
        return $this->repeatPassword;
    }

    /**
     * Set the new password repeated
     *
     * @param string $repeatPassword
     */
    public function setRepeatPassword($repeatPassword)
    {
        $this->repeatPassword = $repeatPassword;
    }
}