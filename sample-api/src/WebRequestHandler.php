<?php
namespace SampleApi;

use Commando\Web\JsonRequest;
use Commando\Web\MatchedRequest;
use Commando\Web\Method;
use Commando\Web\PathRoute;
use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Route;
use Commando\Web\Router;
use Pimple\Container;
use SampleApi\Core\NotFoundResponse;
use SampleApi\Core\RootHandler;

class WebRequestHandler implements RequestHandler
{
    public function __construct(Application $app)
    {
        $this->container = new Container();
        $this->container['guard'] = function () use ($app) {
            return $app->getGuard();
        };
        $this->container['root-handler'] = function () use ($app) {
            return new RootHandler($app->getConfig());
        };
        $this->container['user-module'] = function () use ($app) {
            return $app->getUserModule();
        };
        $this->container['note-module'] = function () use ($app) {
            return $app->getNoteModule();
        };

        $this->router = new Router([
            new Route('home', Method::ANY, '/', 'root-handler'),
            new PathRoute('user-module', '/users', 'user-module'),
            new PathRoute('note-module', '/notes', 'note-module'),
        ]);
    }

    public function handle(Request $request)
    {
        $matchedRoute = $this->router->match($request);
        if ($matchedRoute === null) {
            return new NotFoundResponse('Route not found');
        }
        $matchedRequest = new MatchedRequest($request, $matchedRoute);

        $handler = $this->container[$matchedRoute->getValue()];
        $response = $handler->handle($matchedRequest);

        return $response;
    }
}