<?php
namespace Sample\Rest\Handler;

use Sample\Core\NotFoundResponse;
use Sample\Rest\ResourceResponse;
use Sample\Security\AuthenticatedRequest;

class GetHandler extends AbstractHandler
{
    public function handle(AuthenticatedRequest $request)
    {
        $id = $request->getMatchedRoute()->getParam('id');
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