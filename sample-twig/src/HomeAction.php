<?php
namespace SampleTwig;

use Commando\Web\Request;

class HomeAction implements Action
{
    public function handle(Request $request)
    {
        parse_str($request->getQueryString(), $params);
        return new View('home', [
            'name' => isset($params['name']) ? $params['name'] : 'Anonymous Coward'
        ]);
    }
}