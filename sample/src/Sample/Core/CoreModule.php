<?php
namespace Sample\Core;

use Commando\Application;
use Commando\Module;
use Commando\Web\RequestMethod;
use Commando\Web\Route;
use PDO;
use Pimple\Container;

class CoreModule implements Module
{
    private $services;

    public function __construct(array $config)
    {
        $this->services = new Container();
        $this->services['database'] = function () use ($config) {
            $pdo = new PDO('sqlite:' . $config['database']['path']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        };
        $this->services['home-handler'] = function () use ($config) {
            return new GetHomeHandler($config);
        };
    }

    public function bootstrap(Application $application)
    {
        $application->addRoute(
            'home',
            new Route(RequestMethod::ANY, '/home', $this->services->raw('home-handler'))
        );
    }

    /**
     * @return PDO
     */
    public function getDatabase()
    {
        return $this->services['database'];
    }
}