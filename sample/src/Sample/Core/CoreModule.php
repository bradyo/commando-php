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
        $this->services['pdo'] = function () use ($config) {
            $dsn = $config['database']['dsn'];
            $user = $config['database']['user'];
            $pass = $config['database']['pass'];
            return new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        };
        $this->services['get-home-handler'] = function () use ($config) {
            return new GetHomeHandler($config);
        };
    }

    public function getRoutes()
    {
        return [
            'get-home' => new Route(RequestMethod::GET, '/home', $this->services['get-home-handler']),
        ];
    }
}