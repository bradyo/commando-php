<?php
namespace Sample\User;

use Commando\Error;
use Commando\Validator\MinLengthValidator;

class UserPostValidator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserPost $userPost
     * @return Error[]
     */
    public function validate(UserPost $userPost)
    {
        $errorMessages = [];
        $existingUser = $this->userRepository->findOneByEmail($userPost->getEmail());
        if ($existingUser !== null) {
            $errorMessages[] = new Error(
                'email',
                'A user with that email already exists'
            );
        }

        $minPasswordLength = 6;
        $lengthValidator = new MinLengthValidator($minPasswordLength);
        if (! $lengthValidator->isValid($userPost->getPassword())) {
            $errorMessages[] = new Error(
                'password',
                'Password must be at least ' . $minPasswordLength . ' characters'
            );
        } else {
            if ($userPost->getPasswordRepeat() !== $userPost->getPassword()) {
                $errorMessages[] = new Error(
                    'passwordRepeat',
                    'Password repeat does not match password'
                );
            }
        }

        return $errorMessages;
    }
}