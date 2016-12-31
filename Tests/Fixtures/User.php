<?php
namespace Tath\FormBundle\Tests\Fixtures;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private $roles = [];

    public static function make($roles)
    {
        $user = new User();
        $user->setRoles($roles);
        return $user;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword()
    {
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
    }

    public function eraseCredentials()
    {
    }
}