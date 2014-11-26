<?php
namespace Sample\Rest;

use Commando\Web\Json\JsonResponse;
use Commando\Web\Request;

class ResourceResponse extends JsonResponse
{
    public function __construct(Resource $resource, Request $request)
    {
        $links = [];
        foreach ($resource->getLinks() as $link) {
            $link['uri'] = $request->getSchemeAndHttpHost() . '/' . $link['uri'];
            $links[] = $link;
        }

        $content = [
            'uri' => $request->getSchemeAndHttpHost() . '/' . $resource->getUri(),
            'data' => $resource->getData(),
            'links' =>$links
        ];

        parent::__construct($content, 200);
    }
}