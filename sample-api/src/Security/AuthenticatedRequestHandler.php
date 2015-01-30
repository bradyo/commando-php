<?php
namespace SampleApi\Security;

use Commando\Web\MatchedRoute;
use Commando\Web\Response;

interface AuthenticatedRequestHandler
{
    /**
     * @param AuthenticatedRequest $request
     * @param MatchedRoute $matchedRoute
     * @return Response
     */
    public function handle(AuthenticatedRequest $request, MatchedRoute $matchedRoute);
}