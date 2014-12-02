<?php
namespace TwigSample;

use Commando\Web\Request;

class HomeAction implements Action
{
    public function handle(Request $request)
    {
        return new View('home', [
            'name' => $request->query->get('name', 'Anonymous Coward')
        ]);
    }
}