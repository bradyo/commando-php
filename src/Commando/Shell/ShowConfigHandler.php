<?php
namespace Commando\Shell;

class ShowConfigHandler implements ShellHandler
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $args
     */
    public function handle(array $args)
    {
        print_r($this->config);
    }
}