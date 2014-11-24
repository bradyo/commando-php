<?php
namespace Sample\User;

use Commando\Web\Json\JsonResponse;

class UserResponse extends JsonResponse
{
    public function __construct(User $user, $statusCode = 200)
    {
        $data = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ];
        parent::__construct($data, $statusCode);
    }
}