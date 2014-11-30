<?php
namespace Sample;

use Commando\Web\MatchedRoute;
use Commando\Web\Method;
use Commando\Web\PathRoute;
use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Route;
use Commando\Web\Router;
use Pimple\Container;
use Sample\Core\ErrorResponse;
use Sample\Core\NotFoundResponse;
use Sample\Core\RootHandler;
use Symfony\Component\HttpFoundation\ParameterBag;

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

    public function handle(Request $parentRequest, MatchedRoute $parentRoute)
    {
        try {
            $request = $this->convertRequestBody($parentRequest);
        } catch (\Exception $e) {
            return new ErrorResponse("Failed to convert request body");
        }

        $route = $this->router->match($request);
        if ($route === null) {
            return new NotFoundResponse('Route not found');
        }
        $handler = $this->container[$route->getHandlerName()];

        return $handler->handle($request, $route);
    }

    private function convertRequestBody(Request $request)
    {
        $convertedRequest = clone($request);
        $method = strtoupper($request->server->get('REQUEST_METHOD', 'GET'));
        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            // parse request content into params
            $data = [];
            if (0 === strpos($request->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')) {
                parse_str($request->getContent(), $data);
            } else if (0 === strpos($request->headers->get('CONTENT_TYPE'), 'application/json')) {
                $data = json_decode($request->getContent(), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception("Failed to decode JSON string");
                }
            }
            $convertedRequest->request = new ParameterBag($data);
        }
        return $convertedRequest;
    }
}