<?php
namespace Sample\User;

use Commando\Web\Json\JsonResponse;
use Commando\Web\Method;
use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Route;
use Pimple\Container;
use Sample\Application;
use Sample\Security\GuardedRequestHandler;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class UserModule implements RequestHandler
{
    private $routes;
    private $container;

    public function __construct(Application $app)
    {
        $this->routes = new RouteCollection();

        $this->container = new Container();
        $this->container['guard'] = function () use ($app) {
            return $app->getGuard();
        };
        $this->container['user-repository'] = function () use ($app) {
            return new UserRepository($app->getDatabase());
        };
        $this->container['user-service'] = function () {
            return new UserService($this->container['user-repository']);
        };
        $this->container['user-post-validator'] = function () {
            return new UserPostValidator($this->container['user-repository']);
        };
        $this->container['get-handler'] = function () {
            return new GetUserHandler($this->container['user-repository']);
        };
        $this->container['list-handler'] = function () {
            return new ListUsersHandler($this->container['user-repository']);
        };
        $this->container['post-handler'] = function () {
            return new PostUserHandler(
                $this->container['user-post-validator'],
                $this->container['user-service']
            );
        };

        $this->routes->add(
            'get-user',
            new Route(Method::GET, '/users/{id}', $this->container->raw('get-handler'))
        );
        $this->routes->add(
            'list-users',
            new Route(Method::GET, '/users', $this->container->raw('list-handler'))
        );
        $this->routes->add(
            'post-user',
            new Route(Method::POST, '/users', $this->container->raw('post-handler'))
        );
    }

    public function handle(Request $request)
    {
        $context = new RequestContext();
        $context->fromRequest($request);
        $matcher = new UrlMatcher($this->routes, $context);
        $parameters = $matcher->matchRequest($request);
        if (! isset($parameters['handler'])) {
            return new JsonResponse('Not found', 404);
        }
        $request->attributes->add($parameters);

        $handler = call_user_func($parameters['handler']);
        $guardedHandler = new GuardedRequestHandler($this->container['guard'], $handler);

        return $guardedHandler->handle($request);
    }
}