<?php
namespace TwigSample;

use Commando\Web\Method;
use Commando\Web\Request;
use Commando\Web\Route;
use Commando\Web\Router;

class RequestHandler implements \Commando\Web\RequestHandler
{
    private $router;
    private $twig;
    private $notFoundAction;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
        $this->router = new Router([
            new Route('get-home', Method::GET, '/', new HomeAction()),
            new Route('get-page', Method::GET, '/page', new PageAction()),
            new Route('get-error', Method::GET, '/error', new ErrorAction()),
        ]);
        $this->notFoundAction = new NotFoundAction();
    }

    public function handle(Request $request)
    {
        $route = $this->router->match($request);
        if ($route === null) {
            $view = $this->notFoundAction->handle($request);
            return new ViewResponse($this->twig, $view, 404);
        }

        $action = $route->getValue();
        $view = $action->handle($request);

        return new ViewResponse($this->twig, $view);
    }
}