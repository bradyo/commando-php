<?php
namespace Commando;

use Exception;

interface ExceptionHandler
{
    /**
     * @param Exception $exception
     */
    public function handle(Exception $exception);
}