<?php
namespace SampleApi\User;

use SampleApi\Core\ValidationError;

class UserPostValidator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserPost $userPost
     * @return ValidationError[]
     */
    public function validate(UserPost $userPost)
    {
        $errors = [];
        $existingUser = $this->userRepository->findByEmail($userPost->getEmail());
        if ($existingUser !== null) {
            $errors[] = new ValidationError(
                'email',
                'A user with that email already exists'
            );
        }

        $minPasswordLength = 6;
        if (strlen($userPost->getPassword()) < $minPasswordLength) {
            $errors[] = new ValidationError(
                'password',
                'Password must be at least ' . $minPasswordLength . ' characters'
            );
        } else {
            if ($userPost->getPasswordRepeat() !== $userPost->getPassword()) {
                $errors[] = new ValidationError(
                    'passwordRepeat',
                    'Password repeat does not match password'
                );
            }
        }

        return $errors;
    }
}