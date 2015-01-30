<?php
namespace Commando\Web;

class MatchedRequest extends JsonRequest
{
    use RequestDecorator;

    private $matchedRoute;

    public function __construct(Request $request, MatchedRoute $matchedRoute)
    {
        $this->request = new JsonRequest($request);
        $this->matchedRoute = $matchedRoute;
    }

    public function getMatchedRoute()
    {
        return $this->matchedRoute;
    }

    public function getData()
    {
        return $this->request->getData();
    }
}