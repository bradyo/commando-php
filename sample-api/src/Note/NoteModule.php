<?php
namespace SampleApi\Note;

use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Pimple\Container;
use SampleApi\Application;
use SampleApi\Rest\ResourceConfig;
use SampleApi\Rest\RestHandler;

class NoteModule implements RequestHandler
{
    private $container;

    public function __construct(Application $app)
    {
        $this->container = new Container();
        $this->container['guard'] = function () use ($app) {
            return $app->getGuard();
        };
        $this->container['resource-repository'] = function () use ($app) {
            return $app->getResourceRepository();
        };
        $this->container['repository'] = function () {
            return new NoteRepository();
        };
        $this->container['resource-config'] = function () {
            return new NoteResourceConfig($this->container['repository']);
        };
        $this->container['rest-handler'] = function () {
            return new RestHandler(
                $this->container['guard'],
                $this->container['resource-repository'],
                $this->container['resource-config']
            );
        };
    }

    public function handle(Request $request)
    {
        $handler = $this->container['rest-handler'];
        return $handler->handle($request);
    }

    /**
     * @return ResourceConfig
     */
    public function getResourceConfig()
    {
        return $this->container['resource-config'];
    }
}
