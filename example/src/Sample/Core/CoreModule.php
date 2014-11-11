<?php
namespace Sample\Core;

use PDO;

class CoreModule
{
    private $database;

    public function __construct(array $config)
    {
        $this->database = new PDO('mysql:host=localhost', 'root', 'vagrant', array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ));
    }

    /**
     * @return PDO
     */
    public function database()
    {
        return $this->database;
    }
}