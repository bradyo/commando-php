<?php
namespace Sample\Rest\Handler;

use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Sample\Rest\ResourceRepository;
use Sample\Rest\ResourceResponse;

class GetHandler implements RequestHandler
{
    private $repository;

    public function __construct(ResourceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Request $request)
    {
        $id = $request->fromRoute('id');
        $resource = $this->repository->find($id);

        return new ResourceResponse($resource, $request);
    }
}