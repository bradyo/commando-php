<?php
namespace SampleApi\Rest\Handler;

use SampleApi\Core\NotFoundResponse;
use SampleApi\Rest\ResourceResponse;
use SampleApi\Rest\RestRequest;

class GetHandler extends AbstractHandler
{
    public function handle(RestRequest $request)
    {
        $id = $request->getParam('id');

        parse_str($request->getQueryString(), $params);
        if (isset($params['expand'])) {
            $expand = explode(',', $params['expand']);
        } else {
            $expand = [];
        }

        $class = $this->config->getClass();
        $resource = $this->repository->find($class, $id, $expand);
        if ($resource === null) {
            return new NotFoundResponse("Not found");
        } else {
            return new ResourceResponse($resource, $request);
        }
    }
}