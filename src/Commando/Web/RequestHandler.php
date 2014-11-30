<?php
namespace Commando\Web;

interface RequestHandler
{
    /**
     * @param Request $request
     * @param MatchedRoute $route
     * @return Response
     */
    public function handle(Request $request, MatchedRoute $route);
}