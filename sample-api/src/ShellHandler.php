<?php
namespace SampleApi;

use Commando\Shell\Command;
use Commando\Shell\ShowConfigHandler;
use Pimple\Container;
use SampleApi\Core\RootShellHandler;

class ShellHandler implements \Commando\Shell\ShellHandler
{
    private $container;

    public function __construct(Application $app)
    {
        $this->container = new Container();
        $this->container['default'] = function () use ($app) {
            return new RootShellHandler($app->getConfig());
        };
        $this->container['show-config'] = function () use ($app) {
            return new ShowConfigHandler($app->getConfig());
        };
    }

    public function handle(Command $command)
    {
        $handler = $this->getHandler($command->getName());
        $handler->handle($command);
    }

    /**
     * @param $name
     * @return \Commando\Shell\ShellHandler|null
     */
    private function getHandler($name)
    {
        if (isset($this->container[$name])) {
            return $this->container[$name];
        } else {
            return $this->container['default'];
        }
    }
}