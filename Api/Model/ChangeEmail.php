<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

/**
 * Class ChangePassword
 * @package Ant\Bundle\ChateaClientBundle\Api\Model
 */
class ChangeEmail
{
    /**
     * @var string old password
     */
    private $password;

    /**
     * @var string new password
     */
    private $email;

    /**
     * Set the password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }
}