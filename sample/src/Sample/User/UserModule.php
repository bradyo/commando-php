<?php
namespace Sample\User;

use Commando\Web\Json\JsonResponse;
use Commando\Web\Method;
use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Route;
use Pimple\Container;
use Sample\Application;
use Sample\Core\NotFoundResponse;
use Sample\Rest\ResourceConfig;
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

        $this->routes = new RouteCollection();
        $this->addRoute('get-user', Method::GET, '/users/{id}', 'get-handler');
        $this->addRoute('list-user', Method::GET, '/users', 'list-handler');
        $this->addRoute('post-user', Method::POST, '/users', 'post-handler');
    }

    private function addRoute($name, $method, $value, $handlerName)
    {
        $route = new Route($method, $value, $this->container->raw($handlerName));
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
            return new NotFoundResponse("Not found");
        }
        $request->attributes->add($parameters);

        $handler = call_user_func($parameters['handler']);
        $guardedHandler = new GuardedRequestHandler($this->container['guard'], $handler);

        return $guardedHandler->handle($request);
    }

    /**
     * @return ResourceConfig
     */
    public function getResourceConfig()
    {
        return $this->container['resource-config'];
    }
}