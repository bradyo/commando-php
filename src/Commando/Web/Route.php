<?php
namespace Commando\Web;

class Route
{
    private $name;
    private $method;
    private $path;
    private $handlerName;

    protected $isGreedy = false;

    /**
     * @param string $name
     * @param string $method
     * @param string $path
     * @param string $handlerName
     */
    public function __construct($name, $method, $path, $handlerName)
    {
        $this->name = $name;
        $this->method = $method;
        $this->path = $path;
        $this->handlerName = $handlerName;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getHandlerName()
    {
        return $this->handlerName;
    }

    public function isGreedy()
    {
        return $this->isGreedy;
    }
}