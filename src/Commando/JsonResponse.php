<?php
namespace Commando;

class JsonResponse
{
    private $statusCode;
    private $content;

    public function __construct($statusCode = 200, array $content = array(), array $headers = array()) {
        $this->statusCode = $statusCode;
        $this->content = $content;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getContent()
    {
        return $this->content;
    }
}