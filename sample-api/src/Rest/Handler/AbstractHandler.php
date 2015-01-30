<?php
namespace SampleApi\Rest\Handler;

use SampleApi\Core\ErrorResponse;
use SampleApi\Rest\ResourceConfig;
use SampleApi\Rest\ResourceRepository;
use SampleApi\Rest\RestRequest;

abstract class AbstractHandler
{
    protected $repository;
    protected $config;

    public function __construct(ResourceRepository $repository, ResourceConfig $config)
    {
        $this->repository = $repository;
        $this->config = $config;
    }

    public function handle(RestRequest $request)
    {
        return new ErrorResponse('Not implemented');
    }
}