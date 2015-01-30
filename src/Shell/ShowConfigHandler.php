<?php
namespace Commando\Shell;

class ShowConfigHandler implements ShellHandler
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function handle(Command $command)
    {
        print_r($this->config);
    }
}