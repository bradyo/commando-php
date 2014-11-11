<?php
namespace Sample\User;

use Sample\Core\GetHomeHandler;

class CoreRouteProvider
{
    public function getRoutes()
    {
        return [
            [
                'name' => 'get-home',
                'value' => '/',
                'method' => 'get',
                'handler' => GetHomeHandler::class
            ],
        ];
    }
}