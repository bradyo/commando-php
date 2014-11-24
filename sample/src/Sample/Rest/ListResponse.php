<?php
namespace Sample\Rest;

use Commando\Web\Json\JsonResponse;
use Commando\Web\Request;
use Commando\Web\Response;

class ListResponse extends Response
{
    public function __construct(array $resources, Request $request)
    {
        $content = [
            'status' => 'success',
            'data' => [
                'total' => 100,
                'offset' => 0,
                'items' => [],
            ],
            'links' => [
                'next' => [
                    'rel' => 'next',
                    'href=' => $request->getUri() . '?offset=100'
                ]
            ]
        ];
        return new JsonResponse($content, 200);
    }
}