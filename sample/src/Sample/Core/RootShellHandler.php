<?php
namespace Sample\Core;

use Commando\Shell\Command;
use Commando\Shell\ShellHandler;

class RootShellHandler implements ShellHandler
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function handle(Command $command)
    {
        $data = [
            'name' => 'Sample Application',
            'environment' => $this->config['environment'],
            'version' => $this->config['version'],
        ];
        print_r($data);
        echo "\n";
    }
}