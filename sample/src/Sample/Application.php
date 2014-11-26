<?php
namespace Sample;

use Commando\Application as CommandoApplication;
use Commando\Web\Method;
use Commando\Web\Route;
use Pimple\Container;
use Sample\Note\NoteModule;
use Sample\Rest\ResourceRepository;
use Sample\Security\Guard;
use PDO;
use Sample\User\UserModule;

class Application extends CommandoApplication
{
    private $container;

    public function __construct($configPath)
    {
        parent::__construct($configPath);

        $this->container = new Container();
        $this->container['database'] = function () {
            $pdo = new PDO('sqlite:' . $this->getConfig()['database']['path']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        };
        $this->container['guard'] = function () {
            return new Guard();
        };
        $this->container['user-module'] = function () {
            return new UserModule($this);
        };
        $this->container['note-module'] = function () {
            return new NoteModule($this);
        };
        $this->container['resource-repository'] = function () {
            return new ResourceRepository([
                $this->getUserModule()->getResourceConfig(),
                $this->getNoteModule()->getResourceConfig(),
            ]);
        };

        $this->addRoute('home', new Route(Method::ANY, '/', new RootHandler($this->getConfig())));

        $this->addPathRoute('user-module', 'users', 'user-module');
        $this->addPathRoute('note-module', 'notes', 'note-module');
    }

    private function addPathRoute($name, $path, $handlerName)
    {
        $route = new Route(
            Method::ANY,
            '/{match}',
            $this->container->raw($handlerName),
            ['match' => $path . '.*']
        );
        $this->addRoute($name, $route);
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
     * @return UserModule
     */
    public function getUserModule()
    {
        return $this->container['user-module'];
    }

    /**
     * @return NoteModule
     */
    public function getNoteModule()
    {
        return $this->container['note-module'];
    }

    /**
     * @return ResourceRepository
     */
    public function getResourceRepository()
    {
        return $this->container['resource-repository'];
    }
}