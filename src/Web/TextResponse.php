<?php
namespace Commando\Web;

class TextResponse implements Response
{
    private $statusCode;
    private $headers;
    private $body;

    function __construct($body = '',  $statusCode = 200, $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getBody()
    {
        return $this->body;
    }
}