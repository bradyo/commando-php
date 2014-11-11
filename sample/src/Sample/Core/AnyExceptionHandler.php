<?php
namespace Sample\Core;

class AnyExceptionHandler implements ExceptionHandler
{
    public function handle(Exception $exception)
    {
        return new ErrorResponse($exception->getMessage());
    }
}