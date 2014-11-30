<?php
namespace Commando\Web;

class Route
{
    private $name;
    private $method;
    private $path;
    private $value;

    /**
     * @param string $name
     * @param string $method
     * @param string $path
     * @param mixed $value
     */
    public function __construct($name, $method, $path, $value)
    {
        $this->name = $name;
        $this->method = $method;
        $this->path = $path;
        $this->value = $value;
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

    public function getValue()
    {
        return $this->value;
    }

    public function isGreedy()
    {
        return false;
    }
}