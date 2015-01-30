<?php
namespace Commando\Utility;

use Exception;

class ExceptionUtility
{
    public static function getFullTraceAsString(Exception $exception)
    {
        $result = "";
        $count = 0;
        foreach ($exception->getTrace() as $frame) {
            $args = "";
            if (isset($frame['args'])) {
                $args = array();
                foreach ($frame['args'] as $arg) {
                    if (is_string($arg)) {
                        $args[] = "'" . $arg . "'";
                    } elseif (is_array($arg)) {
                        $args[] = "Array";
                    } elseif (is_null($arg)) {
                        $args[] = 'NULL';
                    } elseif (is_bool($arg)) {
                        $args[] = ($arg) ? "true" : "false";
                    } elseif (is_object($arg)) {
                        $args[] = get_class($arg);
                    } elseif (is_resource($arg)) {
                        $args[] = get_resource_type($arg);
                    } else {
                        $args[] = $arg;
                    }
                }
                $args = join(", ", $args);
            }
            $result .= sprintf( "#%s %s(%s): %s(%s)\n",
                $count,
                isset($frame['file']) ? $frame['file'] : '',
                isset($frame['line']) ? $frame['line'] : '',
                isset($frame['function']) ? $frame['function'] : '',
                $args );
            $count++;
        }

        return $result;
    }
}