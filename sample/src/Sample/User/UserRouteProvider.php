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
                'handler' => ListUsersHandler::class
            ],
            [
                'name' => 'post-user',
                'value' => '/users',
                'method' => 'post',
                'handler' => PostUserHandler::class
            ],
            [
                'name' => 'get-user',
                'value' => '/users/{id}',
                'method' => 'get',
                'handler' => GetUserHandler::class
            ],
            [
                'name' => 'delete-user',
                'value' => '/users/{id}',
                'method' => 'delete',
                'handler' => DeleteUserHandler::class
            ],
        ];
    }
}