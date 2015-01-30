<?php
namespace Commando\Web;

use Exception;

interface WebExceptionHandler
{
    /**
     * @param Exception $exception
     * @param Request request
     * @return Response
     */
    public function handle(Exception $exception, Request $request = null);
}