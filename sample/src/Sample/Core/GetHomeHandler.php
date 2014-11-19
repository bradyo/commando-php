<?php
namespace Sample\Core;

use Commando\Web\Json\JsonResponse;
use Commando\Web\Request;
use Commando\Web\RequestHandler;

class GetHomeHandler implements RequestHandler
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function handle(Request $request)
    {
        return new JsonResponse([
            'name' => 'Sample Commando Application',
            'environment' => $this->config['environment'],
            'version' => $this->config['version'],
        ]);
    }
}