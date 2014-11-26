<?php
namespace Sample\User;

use Sample\Rest\EntityRepository;

class UserRepository implements EntityRepository
{
    /**
     * @var User[]
     */
    private $usersById;

    public function __construct()
    {
        $this->usersById = [
            1 => $this->generateUser(1, 'admin@domain.com'),
            2 => $this->generateUser(2, 'somebody1@domain.com'),
            3 => $this->generateUser(3, 'somebody2@domain.com'),
            4 => $this->generateUser(4, 'somebody3@domain.com'),
        ];
    }

    public function find($id)
    {
        if (isset($this->usersById[$id])) {
            return $this->usersById[$id];
        } else {
            return null;
        }
    }

    public function findByEmail($email)
    {
        foreach ($this->usersById as $id => $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }
        return null;
    }

    public function findAll()
    {
        return array_values($this->usersById);
    }

    public function count()
    {
        return count(array_values($this->usersById));
    }

    public function save($user)
    {
        throw new \RuntimeException("Not implements");
    }

    public function remove($id)
    {
        throw new \RuntimeException("Not implements");
    }

    private function generateUser($id, $email)
    {
        $password = "password";
        $passwordSalt = sha1("123");
        $passwordHash = sha1($password . $passwordSalt);
        return new User($id, $email, $passwordHash, $passwordSalt);
    }
}