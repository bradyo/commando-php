<?php
namespace Sample;

use Commando\Application as CommandoApplication;
use Commando\Web\RequestMethod;
use Commando\Web\Route;
use Sample\Core\CoreModule;
use Sample\Security\Guard;
use Sample\User\UserModule;

class Application extends CommandoApplication
{
    public function __construct($configPath)
    {
        parent::__construct($configPath);

        $this->setModule('core', new CoreModule($this->getConfig()));
        $this->setModule('user', new UserModule($this->getCoreModule(), new Guard()));

        $this->addRoute(
            'user-module',
            new Route(RequestMethod::ANY, '/users{subRoute}', $this->getUserModule(), ['subRoute' => '.*'])
        );
    }

    /**
     * @return CoreModule
     */
    public function getCoreModule()
    {
        return $this->getModule('core');
    }

    /**
     * @return UserModule
     */
    public function getUserModule()
    {
        return $this->getModule('user');
    }
}