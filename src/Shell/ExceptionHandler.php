<?php
namespace Commando\Shell;

use Exception;

interface ExceptionHandler
{
    /**
     * @param Exception $exception
     */
    public function handle(Exception $exception);
}