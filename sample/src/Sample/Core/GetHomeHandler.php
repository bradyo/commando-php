<?php
namespace Sample\Core;

use Commando\RequestHandler;

class GetHomeHandler implements RequestHandler
{
    public function handle($request)
    {
        $data = array(
            'name' => 'Commando Application',
            'version' => '1.0',
        );

        return new OkResponse($data);
    }
}