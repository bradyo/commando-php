<?php
namespace SampleApi;

use Pimple\Container;
use SampleApi\Note\NoteModule;
use SampleApi\Rest\ResourceRepository;
use SampleApi\Security\Guard;
use SampleApi\User\UserModule;
use PDO;

class Application extends \Commando\Application
{
    private $config;
    private $container;

    public function __construct(array $config)
    {
        parent::__construct();

        $this->config = $config;

        $this->container = new Container();
        $this->container['database'] = function () {
            $pdo = new PDO('sqlite:' . $this->config['database']['path']);
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

        $this->setShellHandler(new ShellHandler($this));
        $this->setWebRequestHandler(new WebRequestHandler($this));
    }

    /**
     * return array
     */
    public function getConfig()
    {
        return $this->config;
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