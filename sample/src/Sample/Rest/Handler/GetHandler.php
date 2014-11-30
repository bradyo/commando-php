<?php
namespace Sample\Rest\Handler;

use Commando\Web\MatchedRoute;
use Sample\Core\NotFoundResponse;
use Sample\Rest\ResourceResponse;
use Sample\Security\AuthenticatedRequest;

class GetHandler extends AbstractHandler
{
    public function handle(AuthenticatedRequest $request, MatchedRoute $route)
    {
        $id = $route->getParam('id');
        $expand = explode(',', $request->query->get('expand', ''));

        $class = $this->config->getClass();
        $resource = $this->repository->find($class, $id, $expand);
        if ($resource === null) {
            return new NotFoundResponse("Not found");
        } else {
            return new ResourceResponse($resource, $request);
        }
    }
}