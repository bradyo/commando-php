<?php
namespace Sample\Rest\Handler;

use Commando\Web\MatchedRoute;
use Sample\Core\ErrorResponse;
use Sample\Security\AuthenticatedRequest;

class PutHandler extends AbstractHandler
{
    public function handle(AuthenticatedRequest $request, MatchedRoute $route)
    {
        return new ErrorResponse('Resource PUT not implemented');
    }
}