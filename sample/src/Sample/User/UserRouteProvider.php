<?php
namespace Sample\User;

use Commando\Web\Route;

class UserRouteProvider
{
    private $userModule;

    public function __construct(UserModule $userModule)
    {
        $this->userModule = $userModule;
    }

    public function getRoutes()
    {
        $module = $this->userModule;
        return [
            'list-users'  => new Route('get',    '/users',      $module->listUsersHandler()),
            'post-user'   => new Route('post',   '/users',      $module->postUserHandler()),
            'get-user'    => new Route('get',    '/users/{id}', $module->getUserHandler()),
            'delete-user' => new Route('delete', '/users/{id}', $module->deleteUserHandler()),
        ];
    }
}