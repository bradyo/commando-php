<?php
namespace Sample\User;

class UserRepository
{
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find($id) {
        return $this->fakeUser($id, 'bradyaolsen@gmail.com');
    }

    public function findByEmail($email)
    {
        return $this->fakeUser(rand(1, 100), $email);
    }

    public function findAll()
    {
        $users = array();
        for ($i = 1; $i <= 30; $i++) {
            $users[] = $this->fakeUser($i, 'bradyaolsen+' . $i . '@gmail.com');
        }
        return $users;
    }

    private function fakeUser($id, $email)
    {
        $password = "password";
        $passwordSalt = sha1("123");
        $passwordHash = sha1($password . $passwordSalt);
        return new User($id, $email, $passwordHash, $passwordSalt);
    }
}