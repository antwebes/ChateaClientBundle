<?php
namespace Ant\Bundle\ChateaClientBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;

interface ChateaUserProviderInterface extends UserProviderInterface
{
    public function loadUser($username, $password);
}