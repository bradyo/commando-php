<?php
namespace Commando\Web;

use Exception;

interface WebExceptionHandler
{
    /**
     * @param Request request
     * @param Exception $exception
     * @return Response
     */
    public function handle(Request $request, Exception $exception);
}