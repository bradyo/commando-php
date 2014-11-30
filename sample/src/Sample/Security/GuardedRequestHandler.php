<?php
namespace Sample\Security;

use Commando\Web\MatchedRoute;
use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Sample\Core\NotAuthenticatedResponse;

class GuardedRequestHandler implements RequestHandler
{
    private $guard;
    private $securedHandler;

    public function __construct(Guard $guard, AuthenticatedRequestHandler $securedHandler)
    {
        $this->guard = $guard;
        $this->securedHandler = $securedHandler;
    }

    public function handle(Request $request, MatchedRoute $route)
    {
        if ($request->getUserInfo() !== null) {
            $authenticatedRequest = $this->guard->authenticate($request);
            return $this->securedHandler->handle($authenticatedRequest, $route);
        } else {
            return new NotAuthenticatedResponse('Authentication required');
        }
    }
}