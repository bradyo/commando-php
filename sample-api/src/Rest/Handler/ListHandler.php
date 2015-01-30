<?php
namespace SampleApi\Rest\Handler;

use SampleApi\Rest\ResourceResponse;
use SampleApi\Rest\RestRequest;

class ListHandler extends AbstractHandler
{
    public function handle(RestRequest $request)
    {
        parse_str($request->getQueryString(), $params);
        if (isset($params['expand'])) {
            $expand = explode(',', $params['expand']);
        } else {
            $expand = [];
        }

        $class = $this->config->getClass();
        $resource = $this->repository->findAll($class, $expand);

        return new ResourceResponse($resource, $request);
    }
}