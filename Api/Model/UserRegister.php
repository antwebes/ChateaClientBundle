<?php
namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use Symfony\Component\Validator\Constraints as Assert;


class UserRegister
{

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max = 4096)
     */
    protected $plainPassword;



    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }
}