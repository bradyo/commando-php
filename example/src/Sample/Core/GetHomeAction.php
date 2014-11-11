<?php
namespace Sample;

use Commando\Action;

class GetHomeAction implements Action
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