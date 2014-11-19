<?php
namespace Commando\Web;

interface RequestHandler
{
    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request);
}