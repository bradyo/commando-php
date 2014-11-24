<?php
namespace Sample\Security;

use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Response;
use Sample\Core\NotAuthenticatedResponse;

class GuardHandler implements RequestHandler
{
    private $guard;
    private $securedHandler;

    public function __construct(Guard $guard, AuthenticatedRequestHandler $securedHandler)
    {
        $this->guard = $guard;
        $this->securedHandler = $securedHandler;
    }

    /**
     * @param Request $request
     * @return Response
     */
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