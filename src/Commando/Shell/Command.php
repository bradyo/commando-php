<?php
namespace Commando\Shell;

class Command
{
    private $name = null;
    private $params = [];

    public function __construct(array $argv)
    {
        $argc = count($argv);
        if ($argc >= 2) {
            $this->name = $argv[1];
            $this->params = array_slice($argv, 2);
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getParams()
    {
        return $this->params;
    }
}