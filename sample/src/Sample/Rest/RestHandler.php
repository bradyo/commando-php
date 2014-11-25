<?php
namespace Sample\Rest;

use Commando\Web\Json\JsonResponse;
use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Method;
use Commando\Web\Route;
use Pimple\Container;
use Sample\Rest\Handler\DeleteHandler;
use Sample\Rest\Handler\GetHandler;
use Sample\Rest\Handler\ListHandler;
use Sample\Rest\Handler\PostHandler;
use Sample\Rest\Handler\PutHandler;
use Sample\Security\Guard;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class RestHandler implements RequestHandler
{
    private $guard;
    private $resourceConfig;
    private $repository;
    private $container;
    private $routes;

    public function __construct(Guard $guard, ResourceConfig $resourceConfig, ResourceRepository $repository) {
        $this->guard = $guard;
        $this->resourceConfig = $resourceConfig;
        $this->repository = $repository;

        $this->container = new Container();
        $this->container['list-handler'] = function () {
            return $this->secured(new ListHandler($this->repository));
        };
        $this->container['post-handler'] = function () {
            return $this->secured(new PostHandler($this->repository));
        };
        $this->container['get-handler'] = function () {
            return $this->secured(new GetHandler($this->repository));
        };
        $this->container['put-handler'] = function () {
            return $this->secured(new PutHandler($this->repository));
        };
        $this->container['delete-handler'] = function () {
            return $this->secured(new DeleteHandler($this->repository));
        };

        $this->routes = new RouteCollection();
        $this->addRoute('list',   Method::GET,    '/',     'list-handler');
        $this->addRoute('post',   Method::POST,   '/',     'post-handler');
        $this->addRoute('get',    Method::GET,    '/{id}', 'get-handler');
        $this->addRoute('put',    Method::PUT,    '/{id}', 'put-handler');
        $this->addRoute('delete', Method::DELETE, '/{id}', 'delete-handler');
    }

    private function secured(RequestHandler $handler)
    {
        return new SecuredHandler($this->guard, $handler);
    }

    private function addRoute($name, $method, $value, $handlerName)
    {
        $route = new Route(
            $method,
            $this->resourceConfig->getPath() . $value,
            $this->container->raw($handlerName)
        );
        $this->routes->add($name, $route);
    }

    public function handle(Request $request)
    {
        $context = new RequestContext();
        $context->fromRequest($request);
        $matcher = new UrlMatcher($this->routes, $context);
        $parameters = $matcher->matchRequest($request);
        if (! isset($parameters['handler'])) {
            return new JsonResponse('Resource not found', 404);
        }
        $request->attributes->add($parameters);
        $handler = call_user_func($parameters['handler']);

        return $handler->handle($request);
    }
}