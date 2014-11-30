<?php
namespace Commando\Web;

use Commando\Utility\ExceptionUtility;
use Exception;

class DefaultWebExceptionHandler implements WebExceptionHandler
{
    public function handle(Request $request, Exception $exception)
    {
        $content = "Application error: " . $exception->getMessage() . "\n\n";
        $content .= ExceptionUtility::getFullTraceAsString($exception);
        return new Response($content, 500, array());
    }
}