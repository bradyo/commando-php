<?php
namespace Commando\Web;

class DefaultRequestHandler implements RequestHandler
{
    public function handle(Request $request, MatchedRoute $route)
    {
        return new Response('Commando Application', 200);
    }
}