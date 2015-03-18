<?php
/**
 * Created by PhpStorm.
 * User: ant4
 * Date: 13/03/15
 * Time: 10:42
 */

namespace Ant\Bundle\ChateaClientBundle\Form\Model;

class ResetPassword
{
    private $email;

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
}