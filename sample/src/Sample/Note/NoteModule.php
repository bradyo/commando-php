<?php
namespace Sample\Note;

use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Response;
use Pimple\Container;
use Sample\Application;
use Sample\Rest\ResourceConfig;
use Sample\Rest\RestHandler;

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

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        return $this->container['rest-handler']->handle($request);
    }

    /**
     * @return ResourceConfig
     */
    public function getResourceConfig()
    {
        return $this->container['resource-config'];
    }
}
