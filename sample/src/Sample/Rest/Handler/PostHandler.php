<?php
namespace Sample\Rest\Handler;

use Commando\Web\Request;
use Sample\Core\ErrorResponse;

class PostHandler extends AbstractHandler
{
    public function handle(Request $request)
    {
        return new ErrorResponse('Resource POST not implemented');
    }
}