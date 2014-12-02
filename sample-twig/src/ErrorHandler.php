<?php
namespace TwigSample;

use Commando\Web\Request;
use Commando\Web\WebExceptionHandler;

class ErrorHandler implements WebExceptionHandler
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function handle(Request $request, \Exception $exception)
    {
        $view = new View('error', ['message' => $exception->getMessage()]);

        return new ViewResponse($this->twig, $view, 500);
    }
}