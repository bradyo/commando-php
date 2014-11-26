<?php
namespace Sample\Rest\Handler;

use Commando\Web\Request;
use Sample\Rest\ResourceResponse;

class ListHandler extends AbstractHandler
{
    public function handle(Request $request)
    {
        $expand = explode(',', $request->query->get('expand', ''));

        $class = $this->config->getClass();
        $resource = $this->repository->findAll($class, $expand);

        return new ResourceResponse($resource, $request);
    }
}