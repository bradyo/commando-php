<?php
namespace SampleApi\User;

class User
{
    private $id;
    private $email;
    private $passwordHash;
    private $passwordSalt;

    public function __construct($id, $email, $passwordHash, $passwordSalt)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->passwordSalt = $passwordSalt;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    public function getPasswordSalt()
    {
        return $this->passwordSalt;
    }
}