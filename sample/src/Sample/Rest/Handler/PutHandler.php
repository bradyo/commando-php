<?php
namespace Sample\Rest\Handler;

use Sample\Core\ErrorResponse;
use Sample\Security\AuthenticatedRequest;

class PutHandler extends AbstractHandler
{
    public function handle(AuthenticatedRequest $request)
    {
        return new ErrorResponse('Resource PUT not implemented');
    }
}