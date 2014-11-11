<?php
namespace Sample\User;

use Sample\CoreModule;

class UserModule
{
    private $coreModule;

    public function __construct(CoreModule $coreModule)
    {
        $this->coreModule = $coreModule;
    }

    /**
     * @return UserRepository
     */
    public function userRepository()
    {
        return new UserRepository($this->coreModule->database());
    }

    /**
     * @return UserService
     */
    public function userService()
    {
        return new UserService($this->userRepository());
    }

    /**
     * @return UserPostValidator
     */
    public function userPostValidator()
    {
        return new UserPostValidator($this->userRepository());
    }

    /**
     * @return UserRouteProvider
     */
    public function routeProvider()
    {
        return new UserRouteProvider();
    }
}