<?php
namespace Sample\Note;

use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Response;
use Sample\Application;
use Sample\Rest\ResourceConfig;
use Sample\Rest\ResourceRepository;
use Sample\Rest\RestHandler;
use Sample\Security\Guard;

class NoteHandler implements RequestHandler
{
    private $resourceConfig;
    private $restHandler;

    public function __construct(Guard $guard, ResourceRepository $resourceRepository)
    {
        $config = new ResourceConfig(
            'notes',
            'Sample\\Note\\Note',
            [
                'id',
                'authorId',
                'content'
            ],
            [
                'author' => 'Sample\\User\\User'
            ],
            new NoteRepository()
        );
        $this->resourceConfig = $config;

        $this->restHandler = new RestHandler($guard, $config, $resourceRepository);
    }

    public function bootstrap(Application $app)
    {
        $app->getResourceRepository()->addResourceConfig($this->resourceConfig);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        return $this->restHandler->handle($request);
    }
}