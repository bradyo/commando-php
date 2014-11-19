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
        $data = array(
            'name' => 'Sample Commando Application',
            'version' => $this->config['version'],
        );
        return new JsonResponse($data);
    }
}