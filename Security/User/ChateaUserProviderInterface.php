<?php
namespace Ant\Bundle\ChateaClientBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

interface ChateaUserProviderInterface 
{
    public function loadUser($username, $password);
    public function refreshUser(UserInterface $user);
    public function supportsClass($class);
}