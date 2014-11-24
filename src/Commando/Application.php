<?php
namespace Commando;

use Commando\Shell\DefaultExceptionHandler;
use Commando\Shell\DefaultShellHandler;
use Commando\Shell\ShowConfigHandler;
use Commando\Web\ControllerResolver;
use Commando\Web\DefaultRequestHandler;
use Commando\Web\DefaultWebExceptionHandler;
use Commando\Web\Request;
use Commando\Web\RequestMethod;
use Commando\Web\Route;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Exception;
use ErrorException;
use Pimple\Container;

class Application
{
    private $config;
    private $exceptionHandler;
    private $webExceptionHandler;

    /**
     * @var Container of ShellHandler providers
     */
    private $shellHandlers;

    /**
     * @var Container of RequestHandler providers
     */
    private $requestHandlers;

    /**
     * @var RouteCollection
     */
    private $routes;

    /**
     * @var Module[]
     */
    private $modules;

    public function __construct($configPath)
    {
        ini_set('display_startup_errors', 1);
        ini_set('display_errors', 1);
        error_reporting(-1);

        set_error_handler(array($this, 'handleError'));
        set_exception_handler(array($this, 'handleException'));
        register_shutdown_function(array($this, 'handleShutdown'));

        $this->config = require($configPath);
        $this->shellHandlers = new Container();
        $this->requestHandlers = new Container();
        $this->routes = new RouteCollection();
        $this->modules = [];

        $this->exceptionHandler = new DefaultExceptionHandler();
        $this->webExceptionHandler = new DefaultWebExceptionHandler();
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setModule($name, Module $module)
    {
        $this->modules[$name] = $module;
    }

    public function getModule($name)
    {
        return $this->modules[$name];
    }

    public function addRoute($name, Route $route)
    {
        $this->routes->add($name, $route);
    }

    private function bootstrap()
    {
        $this->shellHandlers['default'] = new DefaultShellHandler();
        $this->shellHandlers['get-config'] = new ShowConfigHandler($this->config);

        $this->addRoute('default', new Route(RequestMethod::ANY, '/', new DefaultRequestHandler()));

        foreach ($this->modules as $module) {
            $module->bootstrap($this);
        }
    }

    public function handleError($code, $message, $scriptPath, $lineNumber)
    {
        // Convert all PHP errors to ErrorException
        $severity = 1;
        throw new ErrorException($message, $code, $severity, $scriptPath, $lineNumber, null);
    }

    public function handleException(Exception $e)
    {
        $this->exceptionHandler->handle($e);
    }

    public function handleShutdown()
    {}

    public function handleShell()
    {
        global $argc;
        global $argv;
        try {
            $this->bootstrap();
            if (php_sapi_name() != "cli") {
                throw new ErrorException("Application shell must be run from command line");
            }
            $handler = $this->shellHandlers['default'];
            $params = [];
            if ($argc >= 2) {
                $name = $argv[1];
                $handler = $this->shellHandlers[$name];
                $params = array_slice($argv, 2);
            }
            $handler->handle($params);
        }
        catch (Exception $e) {
            $this->exceptionHandler->handle($e);
        }
    }

    public function handleRequest()
    {
        $this->bootstrap();
        $request = new Request($_GET, $_POST,  array(), $_COOKIE, $_FILES, $_SERVER);
        $this->convertRequestBody($request);

        $matcher = new UrlMatcher($this->routes, new RequestContext());
        $routeListener = new RouterListener($matcher);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($routeListener);

        $resolver = new ControllerResolver();
        $kernel = new HttpKernel($dispatcher, $resolver);

        $response = null;
        try {
            $response = $kernel->handle($request, HttpKernelInterface::MASTER_REQUEST, false);
        }
        catch (Exception $e) {
            $response = $this->webExceptionHandler->handle($request, $e);
        }
        $response->send();
        $kernel->terminate($request, $response);
        exit;
    }

    private function convertRequestBody(Request $request)
    {
        $method = strtoupper($request->server->get('REQUEST_METHOD', 'GET'));
        if (in_array($method, ['PUT', 'DELETE', 'PATCH'])) {
            // parse request content into params
            $data = [];
            if (0 === strpos($request->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')) {
                parse_str($request->getContent(), $data);
            }
            else if (0 === strpos($request->headers->get('CONTENT_TYPE'), 'application/json')) {
                $data = json_decode($request->getContent(), true);
            }
            $request->request = new ParameterBag($data);
        }
    }

    public function getRequestHandler($name)
    {
        return $this->requestHandlers[$name];
    }
}