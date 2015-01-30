<?php
namespace SampleApi\Security;

use Commando\Web\MatchedRequest;
use SampleApi\Core\NotAuthenticatedResponse;

class GuardedRequestHandler
{
    private $guard;
    private $securedHandler;

    public function __construct(Guard $guard, AuthenticatedRequestHandler $securedHandler)
    {
        $this->guard = $guard;
        $this->securedHandler = $securedHandler;
    }

    public function handle(MatchedRequest $request)
    {
        if ($request->getHeader('Authorization') !== null) {
            $accessToken = $this->guard->authenticate($request);
            $authenticatedRequest = new AuthenticatedRequest($request, $accessToken);
            return $this->securedHandler->handle($authenticatedRequest, $request->getMatchedRoute());
        } else {
            return new NotAuthenticatedResponse('Authentication required');
        }
    }
}