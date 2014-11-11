<?php
namespace Commando;

interface ExceptionHandler
{
    /**
     * @param Exception $exception
     * @return Response
     */
    public function handle(Exception $exception);
}