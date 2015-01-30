<?php
namespace SampleApi\User;

use Commando\Web\JsonResponse;

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