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
        echo "Application exception: " . $exception->getMessage() . "\n";
        echo ExceptionUtility::getFullTraceAsString($exception);
    }
}