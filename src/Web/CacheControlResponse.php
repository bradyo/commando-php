<?php
namespace Commando\Web;

class CacheControlResponse implements Response
{
    use ResponseDecorator;

    private $seconds;
    private $directive;

    function __construct(Response $response, Request $request, $seconds, $directive = 'public')
    {
        $this->response = $response;
        $this->seconds = $seconds;
        $this->directive = $directive;
    }

    public function getHeaders()
    {
        $cacheControl = join(' ', [
            $this->directive,
            'max-age=' . $this->seconds,
            's-maxage=' . $this->seconds
        ]);

        return array_merge($this->response->getHeaders(), ['Cache-Control' => $cacheControl]);
    }
}