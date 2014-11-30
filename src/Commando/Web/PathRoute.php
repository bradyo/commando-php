<?php
namespace Commando\Web;

class PathRoute extends Route
{
    /**
     * @param string $name
     * @param string $path
     * @param string $handlerName
     */
    public function __construct($name, $path, $handlerName)
    {
        parent::__construct($name, Method::ANY, $path, $handlerName);
        $this->isGreedy = true;
    }
}