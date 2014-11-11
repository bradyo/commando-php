<?php
namespace Commando;

interface Action
{
    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request);
}