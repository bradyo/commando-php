<?php
namespace Sample\User;

use Commando\Application;
use Commando\Module;
use Commando\Web\Json\JsonResponse;
use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Route;
use Pimple\Container;
use Sample\Core\CoreModule;
use Sample\Security\Guard;
use Sample\Security\GuardedRequestHandler;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class UserModule implements Module, RequestHandler
{
    private $routes;
    private $container;
    private $guard;

    public function __construct(CoreModule $coreModule, Guard $guard)
    {
        $this->guard = $guard;
        $this->routes = new RouteCollection();

        $this->container = new Container();
        $this->container['user-repository'] = function () use ($coreModule) {
            return new UserRepository($coreModule->getDatabase());
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
            new Route('GET', '/users/{id}', $this->container->raw('get-handler'))
        );
        $this->routes->add(
            'list-users',
            new Route('GET', '/users', $this->container->raw('list-handler'))
        );
        $this->routes->add(
            'post-user',
            new Route('POST', '/users', $this->container->raw('post-handler'))
        );
    }

    public function bootstrap(Application $application)
    {}

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
        $guardedHandler = new GuardedRequestHandler($this->guard, $handler);

        return $guardedHandler->handle($request);
    }
}