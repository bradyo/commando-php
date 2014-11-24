<?php
namespace Commando\Web;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

class ControllerResolver implements ControllerResolverInterface
{
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
