<?php
namespace Sample\User;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUser($email, $password)
    {
        $user = $this->userRepository->findByEmail($email);
        $passwordHash = sha1($password . $user->getPasswordSalt());
        if ($passwordHash == $user->getPasswordHash()) {
            return $user;
        }
    }

    public function registerUser(UserPost $post)
    {
        $passwordSalt = sha1(time());
        $passwordHash = sha1($post->getPassword() . $passwordSalt);
        return new User(1, $post->getEmail(), $passwordHash, $passwordSalt);
    }
}