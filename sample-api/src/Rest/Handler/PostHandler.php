<?php
namespace SampleApi\Rest\Handler;

use SampleApi\Core\ErrorResponse;
use SampleApi\Rest\RestRequest;

class PostHandler extends AbstractHandler
{
    public function handle(RestRequest $request)
    {
        return new ErrorResponse('Resource POST not implemented');
    }
}