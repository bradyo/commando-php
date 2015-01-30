<?php
namespace SampleApi\Rest;

use Commando\Web\MatchedRoute;
use Commando\Web\Request;
use Commando\Web\RequestDecorator;
use SampleApi\Security\AccessToken;

class RestRequest implements Request
{
    use RequestDecorator;

    private $matchedRoute;
    private $accessToken;

    public function __construct(Request $request, MatchedRoute $matchedRoute, AccessToken $accessToken)
    {
        $this->request = $request;
        $this->matchedRoute = $matchedRoute;
        $this->accessToken = $accessToken;
    }

    public function getMatchedRoute()
    {
        return $this->matchedRoute;
    }

    public function getParam($name)
    {
        return $this->matchedRoute->getParam($name);
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }
}