<?php
namespace SampleApi\Security;

class AccessToken
{
    private $userId;
    private $roles;

    public function __construct($userId, array $roles)
    {
        $this->userId = $userId;
        $this->roles = $roles;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }
}