<?php
namespace Commando\Web;

use Commando\Utility\ExceptionUtility;
use Exception;

class DefaultWebExceptionHandler implements WebExceptionHandler
{
    private $showTrace;

    public function __construct($showTrace = false)
    {
        $this->showTrace = $showTrace;
    }

    public function handle(Exception $exception, Request $request = null)
    {
        $content = "Application error: " . $exception->getMessage() . "\n";
        if ($this->showTrace) {
            $content .= "\nException trace:\n" . ExceptionUtility::getFullTraceAsString($exception) . "\n";
        }
        return new TextResponse($content, 500);
    }
}