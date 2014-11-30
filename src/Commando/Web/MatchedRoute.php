<?php
namespace Commando\Web;

class MatchedRoute extends Route
{
    private $params;

    public function __construct(Route $route, array $params = [])
    {
        if ($route === null) {
            $route = new Route(null, Method::ANY, '/', null);
        }
        parent::__construct(
            $route->getName(),
            $route->getMethod(),
            $route->getPath(),
            $route->getValue()
        );
        $this->params = $params;
    }

    public function getParam($name)
    {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        } else {
            return null;
        }
    }
}