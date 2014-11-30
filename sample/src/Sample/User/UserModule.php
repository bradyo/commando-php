<?php
namespace Sample\User;

use Commando\Web\MatchedRoute;
use Commando\Web\Method;
use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Route;
use Commando\Web\Router;
use Pimple\Container;
use Sample\Application;
use Sample\Core\NotFoundResponse;
use Sample\Rest\ResourceConfig;
use Sample\Security\GuardedRequestHandler;

class UserModule implements RequestHandler
{
    private $router;
    private $container;

    public function __construct(Application $app)
    {
        $this->container = new Container();
        $this->container['guard'] = function () use ($app) {
            return $app->getGuard();
        };
        $this->container['repository'] = function () use ($app) {
            return new UserRepository($app->getDatabase());
        };
        $this->container['service'] = function () {
            return new UserService($this->container['repository']);
        };
        $this->container['post-validator'] = function () {
            return new UserPostValidator($this->container['repository']);
        };
        $this->container['resource-config'] = function () {
            return new UserResourceConfig($this->container['repository']);
        };
        $this->container['get-handler'] = function () {
            return new GetUserHandler($this->container['repository']);
        };
        $this->container['list-handler'] = function () {
            return new ListUsersHandler($this->container['repository']);
        };
        $this->container['post-handler'] = function () {
            return new PostUserHandler(
                $this->container['post-validator'],
                $this->container['service']
            );
        };

        $this->router = new Router([
            new Route('get-users', Method::GET, '/users', 'list-handler'),
            new Route('post-user', Method::POST, '/users', 'post-handler'),
            new Route('get-user', Method::GET, '/users/{id}', 'get-handler'),
        ]);
    }

    public function handle(Request $request, MatchedRoute $route)
    {
        $route = $this->router->match($request);
        if ($route === null) {
            return new NotFoundResponse('Route not found');
        }
        $handler = new GuardedRequestHandler(
            $this->container['guard'],
            $this->container[$route->getHandlerName()]
        );
        return $handler->handle($request, $route);
    }

    /**
     * @return ResourceConfig
     */
    public function getResourceConfig()
    {
        return $this->container['resource-config'];
    }
}