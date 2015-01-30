<?php
namespace Commando\Web;

trait RequestDecorator
{
    /**
     * @var Request
     */
    private $request;

    public function getRemoteIpAddress()
    {
        return $this->request->getRemoteIpAddress();
    }

    public function getRequestMethod()
    {
        return $this->request->getRequestMethod();
    }

    public function getScheme()
    {
        return $this->request->getScheme();
    }

    public function getServerName()
    {
        return $this->request->getServerName();
    }

    public function getPort()
    {
        return $this->request->getPort();
    }

    public function getUri()
    {
        return $this->request->getUri();
    }

    public function getQueryString()
    {
        return $this->request->getQueryString();
    }

    public function getHeaders()
    {
        return $this->request->getHeaders();
    }

    public function getHeader($name)
    {
        return $this->request->getHeader($name);
    }

    public function getBody()
    {
        return $this->request->getBody();
    }
}