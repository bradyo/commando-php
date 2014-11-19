<?php
namespace Commando\Web;

use Symfony\Component\Routing\Route as SymfonyRoute;

class Route extends SymfonyRoute
{
    public function __construct($method, $path, $handler, $requirements = [])
    {
        $methods = [];
        if ($method !== null && $method != RequestMethod::ANY) {
            $methods = [strtoupper($method)];
        }
        $defaults = ['handler' => $handler];
        $options = [];
        $host = '';
        $schemes = [];
        parent::__construct($path, $defaults, $requirements, $options, $host, $schemes, $methods);
    }
}