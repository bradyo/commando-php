<?php
namespace Commando\Web;

class DefaultRequestHandler implements RequestHandler
{
    public function handle(Request $request)
    {
        return new Response('Commando Application', 200);
    }
}