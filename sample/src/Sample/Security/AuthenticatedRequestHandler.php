<?php
namespace Sample\Security;

use Commando\Web\Response;

interface AuthenticatedRequestHandler
{
    /**
     * @param AuthenticatedRequest $request
     * @return Response
     */
    public function handle(AuthenticatedRequest $request);
}