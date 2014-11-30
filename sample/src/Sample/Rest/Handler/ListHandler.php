<?php
namespace Sample\Rest\Handler;

use Commando\Web\MatchedRoute;
use Sample\Rest\ResourceResponse;
use Sample\Security\AuthenticatedRequest;

class ListHandler extends AbstractHandler
{
    public function handle(AuthenticatedRequest $request, MatchedRoute $route)
    {
        $expand = explode(',', $request->query->get('expand', ''));

        $class = $this->config->getClass();
        $resource = $this->repository->findAll($class, $expand);

        return new ResourceResponse($resource, $request);
    }
}