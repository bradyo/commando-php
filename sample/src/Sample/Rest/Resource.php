<?php
namespace Sample\Rest;

class Resource
{
    private $uri;
    private $data;
    private $links;

    public function __construct($uri, $data, $links)
    {
        $this->uri = $uri;
        $this->data = $data;
        $this->links = $links;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getLinks()
    {
        return $this->links;
    }
}
