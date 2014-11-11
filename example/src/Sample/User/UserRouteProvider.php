<?php
namespace Sample\User;

class UserRouteProvider
{
    public function getRoutes()
    {
        return [
            [
                'name' => 'list-users',
                'value' => '/users',
                'method' => 'get',
                'action' => ListUsersAction::class
            ],
            [
                'name' => 'post-user',
                'value' => '/users',
                'method' => 'post',
                'action' => PostUserAction::class
            ],
            [
                'name' => 'get-user',
                'value' => '/users/{id}',
                'method' => 'get',
                'action' => GetUserAction::class
            ],
            [
                'name' => 'delete-user',
                'value' => '/users/{id}',
                'method' => 'delete',
                'action' => DeleteUserAction::class
            ],
        ];
    }
}