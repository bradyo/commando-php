<?php
namespace Commando;

use Exception;
use ErrorException;
use Symfony\Component\HttpFoundation\ParameterBag;

class Application
{
    private $config;
    private $exceptionHandler;
    private $webExceptionHandler;

    /**
     * @var ShellHandler[]
     */
    private $shellHandlers = array();

    /**
     * @var RequestHandler[]
     */
    private $requestHandlers = array();

    /**
     * @var Module[]
     */
    private $modules;

    public function __construct(array $config)
    {
        ini_set('display_startup_errors', 1);
        ini_set('display_errors', 1);
        error_reporting(-1);

        set_error_handler(array($this, 'handleError'));
        set_exception_handler(array($this, 'handleException'));
        register_shutdown_function(array($this, 'handleShutdown'));

        $this->config = $config;
        $this->exceptionHandler = new DefaultExceptionHandler();
        $this->webExceptionHandler = new DefaultWebExceptionHandler();

        $this->shellHandlers['show-config'] = new ShowConfigHandler($this->config);
    }

    public function bootstrap()
    {
        foreach ($this->modules as $module) {
            $module->bootstrap();
        }
    }

    public function handleError($code, $message, $scriptPath, $lineNumber)
    {
        // Convert all PHP errors to ErrorException
        $severity = 1;
        throw new ErrorException($code, $message, $severity, $scriptPath, $lineNumber);
    }

    public function handleException(Exception $e)
    {
        $this->exceptionHandler->handle($e);
    }

    public function handleShutdown()
    {
    }

    public function handleShell()
    {
        global $argv;
        try {
            $name = $argv[1];
            $handler = $this->shellHandlers[$name];
            $handler->handle(array_slice($argv, 2));
        }
        catch (Exception $e) {
            $this->exceptionHandler->handle($e);
        }
    }

    /**
     * @return Response
     */
    public function handleRequest()
    {
        $request = new Request($_GET, $_POST,  array(), $_COOKIE, $_FILES, $_SERVER);

        $method = strtoupper($request->server->get('REQUEST_METHOD', 'GET'));
        if (in_array($method, array('PUT', 'DELETE', 'PATCH'))) {
            // parse request content into params
            $data = array();
            if (0 === strpos($request->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')) {
                parse_str($request->getContent(), $data);
            }
            else if (0 === strpos($request->headers->get('CONTENT_TYPE'), 'application/json')) {
                $data = json_decode($request->getContent(), true);
            }
            $request->request = new ParameterBag($data);
        }

        try {
            // todo: run request handler
        }
        catch (Exception $e) {
            return $this->webExceptionHandler->handle($e);
        }
    }
}