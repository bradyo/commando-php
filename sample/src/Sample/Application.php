<?php
namespace Sample;

use Commando\Application as CommandoApplication;
use Commando\Web\Method;
use Commando\Web\Route;
use Pimple\Container;
use Sample\Rest\ResourceRepository;
use Sample\Security\Guard;
use PDO;
use Sample\User\UserModule;

class Application extends CommandoApplication
{
    private $container;
    private $modules;

    public function __construct($configPath)
    {
        parent::__construct($configPath);

        $this->container = new Container();
        $this->container['database'] = function () {
            $pdo = new PDO('sqlite:' . $this->getConfig()['database']['path']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        };
        $this->container['resource-repository'] = function () {
            return new ResourceRepository($this->getDatabase());
        };
        $this->container['root-handler'] = function () {
            return new RootHandler($this->getConfig());
        };
        $this->container['user-handler'] = function () {
            return $this->modules['user'];
        };

        $this->modules = [
            'user' => new UserModule($this)
        ];

        $this->addRoute(
            'home',
            new Route(Method::ANY, '/', $this->container->raw('root-handler'))
        );
        $this->addRoute(
            'users',
            new Route(Method::ANY, '/{match}', $this->container->raw('user-handler'), ['match' => 'users.*'])
        );
    }

    /**
     * @return PDO
     */
    public function getDatabase()
    {
        return $this->container['database'];
    }

    /**
     * @return Guard
     */
    public function getGuard()
    {
        return $this->container['guard'];
    }

    /**
     * @return ResourceRepository
     */
    public function getResourceRepository()
    {
        return $this->container['resource-repository'];
    }
}