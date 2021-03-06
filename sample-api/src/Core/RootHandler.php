<?php
namespace SampleApi\Core;

use Commando\Web\JsonResponse;
use Commando\Web\Request;
use Commando\Web\RequestHandler;

class RootHandler implements RequestHandler
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function handle(Request $request)
    {
        return new JsonResponse([
            'name' => 'Sample Application',
            'environment' => $this->config['environment'],
            'version' => $this->config['version'],
        ]);
    }
}