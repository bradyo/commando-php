<?php
namespace Sample\Security;

use Commando\Web\MatchedRoute;
use Commando\Web\Response;

interface AuthenticatedRequestHandler
{
    /**
     * @param AuthenticatedRequest $request
     * @param MatchedRoute $route
     * @return Response
     */
    public function handle(AuthenticatedRequest $request, MatchedRoute $route);
}