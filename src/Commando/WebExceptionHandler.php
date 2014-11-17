<?php
namespace Commando;

use Exception;

interface WebExceptionHandler
{
    /**
     * @param Exception $exception
     * @return Response
     */
    public function handle(Exception $exception);
}