<?php
namespace Sample\Rest;

use Commando\Web\Json\JsonResponse;
use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Method;
use Commando\Web\Route;
use Pimple\Container;
use Sample\Rest\Handler;
use Sample\Security\Guard;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class RestHandler implements RequestHandler
{
    private $guard;
    private $repository;
    private $config;
    private $container;
    private $routes;

    public function __construct(Guard $guard, ResourceRepository $repository, ResourceConfig $config)
    {
        $this->guard = $guard;
        $this->repository = $repository;
        $this->config = $config;

        $this->container = new Container();
        $this->container['list-handler'] = function () {
            return new Handler\ListHandler($this->repository, $this->config);
        };
        $this->container['post-handler'] = function () {
            return new Handler\PostHandler($this->repository, $this->config);
        };
        $this->container['get-handler'] = function () {
            return new Handler\GetHandler($this->repository, $this->config);
        };
        $this->container['put-handler'] = function () {
            return new Handler\PutHandler($this->repository, $this->config);
        };
        $this->container['delete-handler'] = function () {
            return new Handler\DeleteHandler($this->repository, $this->config);
        };

        $this->routes = new RouteCollection();
        $this->addRoute('list',   Method::GET,    '',     'list-handler');
        $this->addRoute('post',   Method::POST,   '',     'post-handler');
        $this->addRoute('get',    Method::GET,    '/{id}', 'get-handler');
        $this->addRoute('put',    Method::PUT,    '/{id}', 'put-handler');
        $this->addRoute('delete', Method::DELETE, '/{id}', 'delete-handler');
    }

    private function addRoute($name, $method, $value, $handlerName)
    {
        $route = new Route(
            $method,
            $this->config->getPath() . $value,
            $this->container->raw($handlerName)
        );
        $this->routes->add($name, $route);
    }

    public function handle(Request $request)
    {
        $context = new RequestContext();
        $context->fromRequest($request);
        $matcher = new UrlMatcher($this->routes, $context);
        try {
            $parameters = $matcher->matchRequest($request);
        } catch (\Exception $e) {
            $parameters = [];
        }
        if (! isset($parameters['handler'])) {
            return new JsonResponse('Resource not found', 404);
        }
        $request->attributes->add($parameters);
        $handler = call_user_func($parameters['handler']);
        $securedHandler = new SecurityHandler($this->guard, $handler);

        return $securedHandler->handle($request);
    }
}