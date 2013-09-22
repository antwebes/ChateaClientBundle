<?php
namespace Ant\Bundle\ChateaClientBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

interface ChateaUserProviderInterface extends UserProviderInterface
{
    public function loadUser($username, $password);
    public function refreshUser(UserInterface $user);
    public function supportsClass($class);
}