<?php
namespace Sample\User;

use Commando\Module;
use Sample\Core\CoreModule;

class UserModule extends Module
{
    private $coreModule;

    public function __construct(CoreModule $coreModule)
    {
        $this->coreModule = $coreModule;
    }

    public function getRoutes()
    {
        return $this->getRouteProvider()->getRoutes();
    }

    public function userRepository()
    {
        return new UserRepository($this->coreModule->getDatabase());
    }

    public function userService()
    {
        return new UserService($this->userRepository());
    }

    public function userPostValidator()
    {
        return new UserPostValidator($this->userRepository());
    }

    public function listUsersHandler()
    {
        return new ListUsersHandler($this->userRepository());
    }

    public function postUserHandler()
    {
        return new PostUserHandler($this->userPostValidator(), $this->userService());
    }

    public function getUserHandler()
    {
        return new GetUserHandler($this->userRepository());
    }

    public function deleteUserHandler()
    {
        return new DeleteUserHandler($this->userRepository());
    }

    public function getRouteProvider()
    {
        return new UserRouteProvider($this);
    }
}