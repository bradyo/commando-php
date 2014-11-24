<?php
namespace Sample\Rest;

use Commando\Web\Json\JsonResponse;
use Commando\Web\Request;
use Commando\Web\Response;

class ResourceResponse extends Response
{
    private $resource;
    private $request;

    public function __construct(Resource $resource, Request $request)
    {
        $expand = $request->query->get('expand', '');
        if (strstr(',', $expand)) {
            $expands = explode(',', $expand);
        } else {
            $expands = array($expand);
        }




        $content = [
            'uri' => $resource->getUri(),
            'data' => $resource->getData(),
            'links' => $resource->getLinks()
        ];
        return new JsonResponse($content, 200);
    }
}