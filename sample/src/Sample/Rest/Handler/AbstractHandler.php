<?php
namespace Sample\Rest\Handler;

use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Sample\Core\ErrorResponse;
use Sample\Rest\ResourceConfig;
use Sample\Rest\ResourceRepository;

class AbstractHandler implements RequestHandler
{
    protected $repository;
    protected $config;

    public function __construct(ResourceRepository $repository, ResourceConfig $config)
    {
        $this->repository = $repository;
        $this->config = $config;
    }

    public function handle(Request $request)
    {
        return new ErrorResponse('Not implemented');
    }
}