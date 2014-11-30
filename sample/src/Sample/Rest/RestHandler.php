<?php
namespace Sample\Rest;

use Commando\Web\MatchedRequest;
use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Method;
use Commando\Web\Route;
use Commando\Web\Router;
use Pimple\Container;
use Sample\Core\NotFoundResponse;
use Sample\Rest\Handler\DeleteHandler;
use Sample\Rest\Handler\GetHandler;
use Sample\Rest\Handler\ListHandler;
use Sample\Rest\Handler\PostHandler;
use Sample\Rest\Handler\PutHandler;
use Sample\Security\Guard;
use Sample\Security\GuardedRequestHandler;

class RestHandler implements RequestHandler
{
    private $guard;
    private $repository;
    private $config;
    private $container;
    private $router;

    public function __construct(Guard $guard, ResourceRepository $repository, ResourceConfig $config)
    {
        $this->guard = $guard;
        $this->repository = $repository;
        $this->config = $config;

        $this->container = new Container();
        $this->container['list-handler'] = function () {
            return new ListHandler($this->repository, $this->config);
        };
        $this->container['post-handler'] = function () {
            return new PostHandler($this->repository, $this->config);
        };
        $this->container['get-handler'] = function () {
            return new GetHandler($this->repository, $this->config);
        };
        $this->container['put-handler'] = function () {
            return new PutHandler($this->repository, $this->config);
        };
        $this->container['delete-handler'] = function () {
            return new DeleteHandler($this->repository, $this->config);
        };

        $path = '/' . $config->getPath();
        $this->router = new Router([
            new Route('list',   Method::GET,    $path,           'list-handler'),
            new Route('post',   Method::POST,   $path,           'post-handler'),
            new Route('get',    Method::GET,    $path . '/{id}', 'get-handler'),
            new Route('put',    Method::PUT,    $path . '/{id}', 'put-handler'),
            new Route('delete', Method::DELETE, $path . '/{id}', 'delete-handler'),
        ]);
    }

    public function handle(Request $request)
    {
        $route = $this->router->match($request);
        if ($route === null) {
            return new NotFoundResponse('Rest route not found');
        }
        $handler = new GuardedRequestHandler(
            $this->guard,
            $this->container[$route->getValue()]
        );
        return $handler->handle(new MatchedRequest($request, $route));
    }
}