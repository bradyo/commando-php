<?php
namespace Sample\Rest\Handler;

use Commando\Web\Request;
use Sample\Core\NotFoundResponse;
use Sample\Rest\ResourceResponse;

class GetHandler extends AbstractHandler
{
    public function handle(Request $request)
    {
        $id = $request->fromRoute('id');
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