<?php
namespace Sample\Core;

use Commando\Module;
use Commando\Web\RequestMethod;
use Commando\Web\Route;
use PDO;
use Pimple\Container;

class CoreModule extends Module
{
    private $services;

    public function __construct(array $config)
    {
        $this->services = new Container();
        $this->services['database'] = function () use ($config) {
            $dsn = $config['database']['dsn'];
            $user = $config['database']['user'];
            $pass = $config['database']['pass'];
            return new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        };
        $this->services['home-handler'] = function () use ($config) {
            return new GetHomeHandler($config);
        };
    }

    public function getRoutes()
    {
        return [
            'home' => new Route(RequestMethod::ANY, '/home', $this->services->raw('home-handler')),
        ];
    }

    /**
     * @return PDO
     */
    public function getDatabase() {
        return $this->services['database'];
    }
}