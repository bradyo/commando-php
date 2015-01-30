<?php
namespace Commando\Web;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class TextRequest implements Request
{
    private $symfonyRequest;

    public function __construct(
        array $query = array(),
        array $request = array(),
        array $cookies = array(),
        array $files = array(),
        array $server = array(),
        $body = null
    ) {
        return $this->symfonyRequest = new SymfonyRequest($query, $request, [], $cookies, $files, $server, $body);
    }

    public function getRemoteIpAddress()
    {
        return $this->symfonyRequest->getClientIp();
    }

    public function getRequestMethod()
    {
        return $this->symfonyRequest->getRealMethod();
    }

    public function getScheme()
    {
        return $this->symfonyRequest->getScheme();
    }

    public function getServerName()
    {
        return $this->symfonyRequest->getHost();
    }

    public function getPort()
    {
        return $this->symfonyRequest->getPort();
    }

    public function getUri()
    {
        return $this->symfonyRequest->getPathInfo();
    }

    public function getQueryString()
    {
        return $this->symfonyRequest->getQueryString();
    }

    public function getHeaders()
    {
        return $this->symfonyRequest->headers->all();
    }

    public function getHeader($name)
    {
        return $this->symfonyRequest->headers->get(strtolower($name));
    }

    public function getBody()
    {
        return $this->symfonyRequest->getContent();
    }
}