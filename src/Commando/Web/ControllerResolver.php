<?php
namespace Commando\Web;

use Commando\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

class ControllerResolver implements ControllerResolverInterface
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getController(Request $request)
    {
        $handler = $request->attributes->get('handler');
        if ($handler === null) {
            return false;
        }
        if (is_callable($handler)) {
            $handler = call_user_func($handler);
        }
        return array($handler, 'handle');
    }

    public function getArguments(Request $request, $controller)
    {
        return array($request);
    }
}
