<?php
namespace Sample\Rest;

use Commando\Module;
use Commando\Web\Route;
use Pimple\Container;
use Sample\Rest\Handler\DeleteHandler;
use Sample\Rest\Handler\GetHandler;
use Sample\Rest\Handler\ListHandler;
use Sample\Rest\Handler\PostHandler;
use Sample\Rest\Handler\PutHandler;

class RestModule extends Module
{
    private $resourceSpec;
    private $services;

    public function __construct(ResourceSpec $spec, ResourceRepository $repository)
    {
        $this->resourceSpec = $spec;

        $this->services = new Container();
        $this->services['get-handler'] = new AuthHandler(new GetHandler($repository));
        $this->services['list-handler'] = new ListHandler($repository);
        $this->services['post-handler'] = new PostHandler($repository);
        $this->services['put-handler'] = new PutHandler($repository);
        $this->services['delete-handler'] = new DeleteHandler($repository);
    }

    public function getRoutes()
    {
        $path = $this->resourceSpec->getPath();
        return [
            'list'   =>  $this->getRoute('list',   $path,           'list-handler'),
            'post'   =>  $this->getRoute('post',   $path,           'post-handler'),
            'get'    =>  $this->getRoute('get',    $path . '/{id}', 'get-handler'),
            'put'    =>  $this->getRoute('put',    $path . '/{id}', 'put-handler'),
            'delete' =>  $this->getRoute('delete', $path . '/{id}', 'delete-handler'),
        ];
    }

    private function getRoute($method, $value, $handlerName)
    {
        return new Route($method, $value, $this->services->raw($handlerName));
    }
}