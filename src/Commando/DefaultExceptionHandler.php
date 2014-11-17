<?php
namespace Commando;

use Commando\Utility\ExceptionUtility;
use Exception;

class DefaultExceptionHandler implements ExceptionHandler
{
    /**
     * @param Exception $exception
     */
    public function handle(Exception $exception)
    {
        echo "Exception occurred: " . $exception->getMessage() . "\n";
        echo ExceptionUtility::getFullTraceAsString($exception);
    }
}