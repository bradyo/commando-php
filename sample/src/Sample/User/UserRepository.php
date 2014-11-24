<?php
namespace Sample\User;

class UserRepository
{
    public function find($id)
    {
        return $this->generateUser($id, 'bradyaolsen@gmail.com');
    }

    public function findByEmail($email)
    {
        return $this->generateUser(rand(1, 100), $email);
    }

    public function findAll()
    {
        $users = array();
        for ($i = 1; $i <= 10; $i++) {
            $users[] = $this->generateUser($i, 'bradyaolsen+' . $i . '@gmail.com');
        }
        return $users;
    }

    private function generateUser($id, $email)
    {
        $password = "password";
        $passwordSalt = sha1("123");
        $passwordHash = sha1($password . $passwordSalt);
        return new User($id, $email, $passwordHash, $passwordSalt);
    }
}