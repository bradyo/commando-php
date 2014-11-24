<?php
namespace Sample\Rest\Handler;

use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Response;
use Sample\Rest\ResourceRepository;

class ListHandler implements RequestHandler
{
    private $repository;

    public function __construct(ResourceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Request $request)
    {
        return new Response('list');
    }
}