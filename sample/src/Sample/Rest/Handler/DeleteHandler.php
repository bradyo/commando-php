<?php
namespace Sample\Rest\Handler;

use Commando\Web\Json\JsonResponse;
use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Sample\Rest\ResourceRepository;

class DeleteHandler implements RequestHandler
{
    private $repository;

    public function __construct(ResourceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Request $request)
    {
        $id = $request->fromRoute('id');
        $this->repository->remove($id);

        return new JsonResponse(['status' => 'success']);
    }
}