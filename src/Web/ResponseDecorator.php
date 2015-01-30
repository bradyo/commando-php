<?php
namespace Commando\Web;

trait ResponseDecorator
{
    /**
     * @var Response
     */
    private $response;

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    public function getBody()
    {
        return $this->response->getBody();
    }
}