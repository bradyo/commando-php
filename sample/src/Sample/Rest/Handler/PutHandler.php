<?php
namespace Sample\Rest\Handler;

use Commando\Web\Request;
use Sample\Core\ErrorResponse;

class PutHandler extends AbstractHandler
{
    public function handle(Request $request)
    {
        return new ErrorResponse('Resource PUT not implemented');
    }
}