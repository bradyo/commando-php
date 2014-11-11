<?php
namespace Sample\User;

use Commando\ArrayView;

class UserView implements ArrayView
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return [
            'id' => $this->user->getId(),
            'email' => $this->user->getEmail(),
        ];
    }
}