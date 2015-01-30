<?php
namespace Commando\Web;

use Aura\Router\Generator;
use Aura\Router\RouteCollection;
use Aura\Router\RouteFactory;

class Router
{
    private $routesByName;
    private $auraRouter;

    /**
     * @param Route[] $routes
     */
    public function __construct($routes = [])
    {
        $this->auraRouter = new \Aura\Router\Router(
            new RouteCollection(new RouteFactory()),
            new Generator()
        );
        foreach ($routes as $route) {
            $path = $route->getPath();
            $auraRoute = $this->auraRouter->getRoutes()->add(
                $route->getName(),
                $path,
                $route->getValue()
            );
            if ($route->isGreedy()) {
                $auraRoute->setWildcard('_match');
            }
            $method = $route->getMethod();
            if ($method !== Method::ANY) {
                $auraRoute->setMethod(strtoupper($method));
            }
            $this->routesByName[$auraRoute->name] = $route;
        }
    }

    /**
     * @param Request $request
     * @return MatchedRoute|null
     */
    public function match(Request $request)
    {
        $serverArguments = [
            'REQUEST_METHOD' => $request->getRequestMethod()
        ];
        $auraRoute = $this->auraRouter->match($request->getUri(), $serverArguments);
        if ($auraRoute === false) {
            return null;
        }
        $route = $this->routesByName[$auraRoute->name];
        $routeParams = $auraRoute->params;

        return new MatchedRoute($route, $routeParams);
    }
}