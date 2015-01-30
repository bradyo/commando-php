<?php
namespace SampleApi\Rest;

use Commando\Web\JsonResponse;
use Commando\Web\Request;

class ResourceResponse extends JsonResponse
{
    public function __construct(Resource $resource, Request $request)
    {
        $links = [];
        foreach ($resource->getLinks() as $link) {
            $link['uri'] = '/' . $link['uri'];
            $links[] = $link;
        }

        $content = [
            'uri' => '/' . $resource->getUri(),
            'data' => $resource->getData(),
            'links' =>$links
        ];

        parent::__construct($content, 200);
    }
}