<?php
namespace Commando\Shell;

use Commando\Utility\ExceptionUtility;
use Exception;

class DefaultExceptionHandler implements ExceptionHandler
{
    /**
     * @param Exception $exception
     */
    public function handle(Exception $exception)
    {
        echo "\n";
        echo "Application error: " . $exception->getMessage() . "\n";
        echo "\n";
        echo ExceptionUtility::getFullTraceAsString($exception) . "\n";
        echo "\n";
        debug_print_backtrace();
        echo "\n";
    }
}