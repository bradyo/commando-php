<?php
namespace Sample\User;

use Commando\Web\Json\JsonResponse;

class UserListResponse extends JsonResponse
{
    /**
     * @param User[] $users
     */
    public function __construct(array $users)
    {
        $itemsData = [];
        foreach ($users as $user) {
            $itemsData[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail()
            ];
        }
        $data = [
            'total' => count($users),
            'items' => $itemsData,
        ];
        parent::__construct($data, 200);
    }
}