<?php
namespace Commando\Web;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest
{
    public function __construct(
        array $query = array(),
        array $request = array(),
        array $attributes = array(),
        array $cookies = array(),
        array $files = array(),
        array $server = array(),
        $content = null,
        MatchedRoute $matchedRoute = null
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->matchedRoute = $matchedRoute;
    }

    public function getMatchedRoute()
    {
        return $this->matchedRoute;
    }
}