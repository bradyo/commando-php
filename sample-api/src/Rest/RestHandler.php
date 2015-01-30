<?php
namespace SampleApi\Rest;

use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Method;
use Commando\Web\Route;
use Commando\Web\Router;
use Pimple\Container;
use SampleApi\Core\NotAuthenticatedResponse;
use SampleApi\Core\NotFoundResponse;
use SampleApi\Rest\Handler\DeleteHandler;
use SampleApi\Rest\Handler\GetHandler;
use SampleApi\Rest\Handler\ListHandler;
use SampleApi\Rest\Handler\PostHandler;
use SampleApi\Rest\Handler\PutHandler;
use SampleApi\Security\Guard;

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

        $this->router = new Router([
            new Route('list',   Method::GET,    '/' . $config->getPath(),           'list-handler'),
            new Route('post',   Method::POST,   '/' . $config->getPath(),           'post-handler'),
            new Route('get',    Method::GET,    '/' . $config->getPath() . '/{id}', 'get-handler'),
            new Route('put',    Method::PUT,    '/' . $config->getPath() . '/{id}', 'put-handler'),
            new Route('delete', Method::DELETE, '/' . $config->getPath() . '/{id}', 'delete-handler'),
        ]);
    }

    public function handle(Request $request)
    {
        if ($request->getHeader('Authorization') !== null) {
            $accessToken = $this->guard->authenticate($request);
        } else {
            return new NotAuthenticatedResponse('Authentication required');
        }

        $matchedRoute = $this->router->match($request);
        if ($matchedRoute === null) {
            return new NotFoundResponse('Rest route not found');
        }

        $handler = $this->container[$matchedRoute->getValue()];
        $request = new RestRequest($request, $matchedRoute, $accessToken);

        return $handler->handle($request);
    }
}