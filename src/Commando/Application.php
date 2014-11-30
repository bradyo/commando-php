<?php
namespace Commando;

use Commando\Shell\Command;
use Commando\Shell\DefaultExceptionHandler;
use Commando\Shell\DefaultShellHandler;
use Commando\Shell\ExceptionHandler;
use Commando\Shell\ShellHandler;
use Commando\Web\DefaultRequestHandler;
use Commando\Web\DefaultWebExceptionHandler;
use Commando\Web\MatchedRoute;
use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\WebExceptionHandler;
use Exception;
use ErrorException;

abstract class Application
{
    private $shellHandler;
    private $shellExceptionHandler;
    private $webRequestHandler;
    private $webExceptionHandler;

    public function __construct()
    {
        ini_set('display_startup_errors', 'on');
        ini_set('display_errors', 'on');
        error_reporting(0);

        set_error_handler(array($this, 'handleError'));
        set_exception_handler(array($this, 'handleException'));
        register_shutdown_function(array($this, 'handleShutdown'));

        $this->shellHandler = new DefaultShellHandler();
        $this->shellExceptionHandler = new DefaultExceptionHandler();
        $this->webRequestHandler = new DefaultRequestHandler();
        $this->webExceptionHandler = new DefaultWebExceptionHandler();
    }

    public function setShellHandler(ShellHandler $handler)
    {
        $this->shellHandler = $handler;
    }

    public function setShellExceptionHandler(ExceptionHandler $handler)
    {
        $this->shellExceptionHandler = $handler;
    }

    public function setWebRequestHandler(RequestHandler $handler)
    {
        $this->webRequestHandler = $handler;
    }

    public function setWebExceptionHandler(WebExceptionHandler $handler)
    {
        $this->webExceptionHandler = $handler;
    }

    public function handleError($code, $message, $scriptPath, $lineNumber)
    {
        throw new ErrorException($message, $code, 1, $scriptPath, $lineNumber, null);
    }

    public function handleException(Exception $e)
    {
        $this->shellExceptionHandler->handle($e);
    }

    public function handleShutdown()
    {
        $error = error_get_last();
        if ($error !== null) {
            $e = new ErrorException($error['message'], $error['type'], 1, $error['file'], $error['line'], null);
            $this->shellExceptionHandler->handle($e);
        }
        exit();
    }

    public function handleShell()
    {
        if (php_sapi_name() != "cli") {
            throw new ErrorException("Command must be run from shell");
        }
        global $argv;
        $request = new Command($argv);
        $this->shellHandler->handle($request);
    }

    public function handleRequest()
    {
        if (php_sapi_name() === "cli") {
            throw new ErrorException("Must run in web request context");
        }
        $request = new Request($_GET, $_POST,  array(), $_COOKIE, $_FILES, $_SERVER);
        try {
            $response = $this->webRequestHandler->handle($request);
        } catch (Exception $e) {
            $response = $this->webExceptionHandler->handle($request, $e);
        }
        $response->send();
    }
}