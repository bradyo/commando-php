<?php
namespace Sample\Core;

use Commando\Module;
use PDO;
use Sample\User\CoreRouteProvider;

class CoreModule implements Module
{
    private $database;

    public function __construct(array $config)
    {
        $this->database = new PDO('mysql:host=localhost', 'root', 'vagrant', array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ));
    }

    public function bootstrap()
    {

    }

    public function getRoutes()
    {
        return $this->getRouteProvider()->getRoutes();
    }


    /**
     * @return PDO
     */
    public function database()
    {
        return $this->database;
    }

    public function getRouteProvider() {
        return new CoreRouteProvider();
    }
}