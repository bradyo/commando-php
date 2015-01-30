<?php
namespace Commando\Web;

use Commando\Utility\ExceptionUtility;
use Exception;

class DefaultWebExceptionHandler implements WebExceptionHandler
{
    public function handle(Exception $exception, Request $request = null)
    {
        $content = "Application error: " . $exception->getMessage() . "\n\n";
        $content .= "Exception trace:\n" . ExceptionUtility::getFullTraceAsString($exception) . "\n";
        return new TextResponse($content, 500, array());
    }
}