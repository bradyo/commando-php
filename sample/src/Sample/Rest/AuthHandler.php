<?php
namespace Sample\Rest;

use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Sample\Core\NotAllowedResponse;
use Sample\Security\Guard;
use Sample\Security\Roles;

class SecuredHandler implements RequestHandler
{
    private $guard;
    private $handler;

    public function __construct(Guard $guard, RequestHandler $handler)
    {
        $this->guard = $guard;
        $this->handler = $handler;
    }

    public function handle(Request $request)
    {
        $authenticatedRequest = $this->guard->authenticate($request);
        if (! $authenticatedRequest->getAccessToken()->hasRole(Roles::ADMIN)) {
            return new NotAllowedResponse("Not allowed");
        }
        return $this->handler->handle($request);
    }
}