<?php
namespace SampleTwig;

use Commando\Web\Request;
use Commando\Web\WebExceptionHandler;
use Exception;

class ErrorHandler implements WebExceptionHandler
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function handle(Exception $exception, Request $request = null)
    {
        $view = new View('error', ['message' => $exception->getMessage()]);

        return new ViewResponse($this->twig, $view, 500);
    }
}