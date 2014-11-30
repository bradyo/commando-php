<?php
namespace Sample\Security;

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

    public function handle(Request $request)
    {
        if ($request->getUserInfo() !== null) {
            $authenticatedRequest = $this->guard->authenticate($request);
            return $this->securedHandler->handle($authenticatedRequest);
        } else {
            return new NotAuthenticatedResponse('Authentication required');
        }
    }
}