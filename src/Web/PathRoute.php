<?php
namespace Commando\Web;

class PathRoute extends Route
{
    /**
     * @param string $name
     * @param string $path
     * @param mixed $value
     */
    public function __construct($name, $path, $value)
    {
        parent::__construct($name, Method::ANY, $path, $value);
    }

    public function isGreedy()
    {
        return true;
    }
}