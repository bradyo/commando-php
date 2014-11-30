<?php
namespace Sample\Rest\Handler;

use Sample\Core\ErrorResponse;
use Sample\Rest\ResourceConfig;
use Sample\Rest\ResourceRepository;
use Sample\Security\AuthenticatedRequest;
use Sample\Security\AuthenticatedRequestHandler;

class AbstractHandler implements AuthenticatedRequestHandler
{
    protected $repository;
    protected $config;

    public function __construct(ResourceRepository $repository, ResourceConfig $config)
    {
        $this->repository = $repository;
        $this->config = $config;
    }

    public function handle(AuthenticatedRequest $request)
    {
        return new ErrorResponse('Not implemented');
    }
}