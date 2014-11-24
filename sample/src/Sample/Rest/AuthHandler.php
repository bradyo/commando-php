<?php
namespace Sample\Rest;

use Commando\Web\Json\JsonResponse;
use Commando\Web\Request;
use Commando\Web\RequestHandler;

class AuthHandler implements RequestHandler
{
    private $handler;

    public function __construct(RequestHandler $handler)
    {
        $this->handler = $handler;
    }

    public function handle(Request $request)
    {
        $isAuthenticated = rand(0, 1);
        if ($isAuthenticated) {
            return $this->handler->handle($request);
        } else {
            return new JsonResponse('Authentication required', 403);
        }
    }
}