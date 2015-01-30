<?php
namespace SampleApi\User;

use Commando\Web\MatchedRequest;
use Commando\Web\Method;
use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Route;
use Commando\Web\Router;
use Pimple\Container;
use SampleApi\Application;
use SampleApi\Core\NotFoundResponse;
use SampleApi\Rest\ResourceConfig;
use SampleApi\Security\GuardedRequestHandler;

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

    public function handle(Request $request)
    {
        $matchedRoute = $this->router->match($request);
        if ($matchedRoute === null) {
            return new NotFoundResponse('Route not found');
        }
        $handler = new GuardedRequestHandler(
            $this->container['guard'],
            $this->container[$matchedRoute->getValue()]
        );
        $matchedRequest = new MatchedRequest($request, $matchedRoute);

        return $handler->handle($matchedRequest);
    }

    /**
     * @return ResourceConfig
     */
    public function getResourceConfig()
    {
        return $this->container['resource-config'];
    }
}