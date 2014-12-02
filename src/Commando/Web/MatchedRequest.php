<?php
namespace Commando\Web;

class MatchedRequest extends Request
{
    public function __construct(Request $request, MatchedRoute $matchedRoute)
    {
        parent::__construct(
            $request->query->all(),
            $request->request->all(),
            $request->attributes->all(),
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all(),
            $request->content,
            $matchedRoute
        );
    }
}