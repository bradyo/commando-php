<?php
namespace Commando;

use Commando\Utility\ExceptionUtility;
use Exception;

class DefaultWebExceptionHandler implements WebExceptionHandler
{
    /**
     * @param Exception $exception
     * @return Response
     */
    public function handle(Exception $exception)
    {
        $trace = ExceptionUtility::getFullTraceAsString($exception);
        return new Response($trace, 500, array());
    }
}