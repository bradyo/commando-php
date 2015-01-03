Commando PHP Framework
======================

The Commando PHP Framework is minimalist framework that provides only the essentials
for building applications for the web and command line.

It deviates from the ubiquitous MVC-style frameworks by providing high-level interfaces
and modular structure for HTTP and Shell requests and leaving most other decisions
up to the developer.

Commando has no View, Model, or DI layers. Those choices are all up to the developer and
can vary between modules as needed.

Commando:
- provides RequestHandler and ShellHandler interfaces
- provides default web and shell error handlers

Principles:
- construct applications in a compositional way using configs and Module objects,
  an Application is just a unit for hold config and Modules and execute commands
- conventional Controller classes replaced by Handler classes, which do only one thing
- provide simple interfaces to implement
- favor composition over inheritance
- favors modular code using composition, not conventions
- provide basic building blocks for developers to extend
- agnostic with respect to Domain Model, View, DI, Validation, Logging, and configuration


Getting Started
===============

![Get Started](http://img2.wikia.nocookie.net/__cb20100729153438/uncyclopedia/images/9/9d/Arnie_shooting.jpg "Get Started")


Commando uses Handlers to processes requests from either the web or command line.

To implement a web `RequestHandler`, you need to create a Handler class and
register it with the application.

```php
namespace Sample;

use Commando\Web\Response;
use Commando\Web\Request;
use Commando\Web\RequestHandler;

class RootHandler implements RequestHandler
{
    public function handle(Request $request)
    {
        return new Response("Success!", 200);
    }
}
```

```php
namespace Sample;

class Application extends \Commando\Application
{
    public function __construct()
    {
        parent::__construct();
        $this->setWebRequestHandler(new RootHandler());
    }
}
```

Make a script to create and execute your application in your web server folder:

```php
require_once(__DIR__ . '/bootstrap.php');
$app = new \Sample\Application();
$app->handleRequest();
```


Request Handler Example
-----------------------

The following example shows just how you might implement a `RequestHandler` to
handle a form POST for user registration:

```php
namespace Sample\User;

use Sample\Core\NotAllowedResponse;
use Sample\Core\ValidationErrorResponse;
use Sample\Security\AuthenticatedRequest;
use Sample\Security\AuthenticatedRequestHandler;
use Sample\Security\Roles;

class PostUserHandler implements AuthenticatedRequestHandler
{
    private $userPostValidator;
    private $userService;

    public function __construct(
        UserPostValidator $userFormValidator,
        UserService $userService
    ) {
        $this->userPostValidator = $userFormValidator;
        $this->userService = $userService;
    }

    public function handle(AuthenticatedRequest $request)
    {
        if (! $request->getAccessToken()->hasRole(Roles::ADMIN)) {
            return new NotAllowedResponse('Not allowed to post Users');
        }

        $userPost = new UserPost($request->request);
        $errors = $this->userPostValidator->validate($userPost);
        if (count($errors) > 0) {
            return new ValidationErrorResponse('Invalid request', $errors);
        }

        $newUser = $this->userService->registerUser($userPost);

        return new UserResponse($newUser, 201);
    }
}
```

Each `RequestHandler` has explicit dependencies, making it much easier a developers
to understand what is going on and also much easier to write unit tests by giving
clear injection points for stubs and mocks.

The control flow is also much easier to follow since the handler must explicitly create
and return a `Response` object or delegate to an object that can give one for it to return.


Delegating to Decorated RequestHandlers
---------------------------------------

The `RequestHandler` interface is used to construct more sophisticated handlers.
For example, the security handler below uses an authentication service to
transform a `Request` into an `AuthenticatedRequest` (decorating the request in
a type-safe way with a security token), and delegates to an `AuthenticatedRequestHandler`
that knows how to deal with the `AuthenticatedRequest`:

```php
namespace Sample\Security;

use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Response;
use Sample\Core\NotAuthenticatedResponse;

interface AuthenticatedRequestHandler
{
    /**
     * @param AuthenticatedRequest $request
     * @return Response
     */
    public function handle(AuthenticatedRequest $request);
}

class GuardHandler implements RequestHandler
{
    private $guard;
    private $authenticatedHandler;

    public function __construct(
        Guard $guard,
        AuthenticatedRequestHandler $authenticatedHandler
    ) {
        $this->guard = $guard;
        $this->authenticatedHandler = $authenticatedHandler;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        if ($request->getUserInfo() !== null) {
            $authenticatedRequest = $this->guard->authenticate($request);
            return $this->authenticatedHandler->handle($authenticatedRequest);
        } else {
            return new Response('Authentication required', 401);
        }
    }
}
```

Compositional Rest Modules
--------------------------

You have to create separate `RequestHandler` classes for every action and that results
in a lot more classes than a conventional MVC architecture that groups actions into
a single class. To take advantage of the separation, you need to create compositions of
individual `RequestHandler` classes into more powerful `RequestHandler` classes.

To demonstrate how this can be done, we will create a `RestHandler` that implements
`RequestHandler` and routes the various GET, POST, PUT, DELETE requests to internal
`RequestHandler` objects managed by `RestHandler`.

```php
class RestRequestHandler implements RequestHandler
{
    public function __construct(ResourceRepository $repository, ResourceConfig $config)
    {
        $listHandler = new ListHandler($repository, $config);
        $postHandler = new PostHandler($repository, $config);
        $getHandler = new GetHandler($repository, $config);
        $putHandler = new PutHandler($repository, $config);
        $deleteHandler = new DeleteHandler($repository, $config);

        $path = $config->getPath();
        $this->router = new Router([
            new Route('list',   Method::GET,    $path,         $listHandler);
            new Route('post',   Method::POST,   $path,         $postHandler);
            new Route('get',    Method::GET,    $path.'/{id}', $getHandler);
            new Route('put',    Method::PUT,    $path.'/{id}', $putHandler);
            new Route('delete', Method::DELETE, $path.'/{id}', $deleteHandler);
        ]);
    }

    public function handle(Request $request)
    {
        $handler = $this->router->match($request);
        return $handler->handle($request);
    }
}
```

Building such a compositional interface takes away all the hard work of
creating individual `RequestHandler` classes for each HTTP method, we need only
create a `RestHandler` with the required arguments. Constructing
such a request handler would look like the following, and is therefore easy to write
test cases against:

```php
// create a RestHandler for resources
$db = new PDO('sqlite:db.sqlite');
$userConfig = new UserResourceConfig(new UserRepository($db));
$noteConfig = new NoteResourceConfig(new NoteRepository($db));
$repository = new ResourceRepository([$userConfig, $noteConfig]);
$noteHandler = new RestRequestHandler($repository, $noteConfig);

// process a request
$request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
$response = $noteHandler->handle($request);
```

Using composition, you can also create a `RequestHandler` one level higher that contains
individual `RestHandler` objects for each resource and delegates down to them using
it's own routing.

```php
class AppRequestHandler implements RequestHandler
{
    public function __construct(ResourceRepository $repository, array $configs)
    {
        $userHandler = new RestRequestHandler($repository, $configs['user']);
        $noteHandler = new RestRequestHandler($repository, $configs['note']);
        $articleHandler = new RestRequestHandler($repository, $configs['article']);

        $this->router = new Router([
            new PathRoute('users',    '/users',    $userHandler),
            new PathRoute('notes',    '/notes',    $noteHandler),
            new PathRoute('articles', '/articles', $articleHandler)
        ]);
    }

    public function handle(Request $request)
    {
        $handler = $this->router->match($request);
        return $handler->handle($request);
    }
}
```


Running the Sample Application
==============================

Browse the sample application in the `sample` folder for more examples.

Start the application on a local port (installs composer dependencies and starts
php webserver on port 8000):

```bash
cd sample/ && ./start.sh
```

```
curl -H 'Content-type: application/json' localhost:8000/users/1
{
    "status": "error",
    "message": "Authentication required"
}
```

```
curl --user admin:password -H 'Content-type: application/json' \
-X POST -d '{"email":"admin@domain.com","password":"12324234"}' \
localhost:8000/users
{
    "status": "error",
    "message": "Invalid request",
    "errors": [
        {
            "name": "email",
            "message": "A user with that email already exists"
        },
        {
            "name": "passwordRepeat",
            "message": "Password repeat does not match password"
        }
    ]
}
```

```
curl --user admin:password -H 'Content-type: application/json' \
localhost:8000/notes?expand=author
{
   "uri": "http:\/\/localhost:8000\/notes",
   "data": {
       "total": 3,
       "items": [
           {
               "id": 1,
               "authorId": 1,
               "content": "hello there",
               "author": {
                   "id": 1,
                   "email": "admin@domain.com"
               }
           },
           {
               "id": 2,
               "authorId": 1,
               "content": "hello again",
               "author": {
                   "id": 1,
                   "email": "admin@domain.com"
               }
           },
           {
               "id": 3,
               "authorId": 2,
               "content": "oh hai",
               "author": {
                   "id": 2,
                   "email": "somebody1@domain.com"
               }
           }
       ]
   },
   "links": [
       {
           "rel": "self",
           "uri": "http:\/\/localhost:8000\/notes"
       },
       {
           "rel": "first",
           "uri": "http:\/\/localhost:8000\/notes?offset=0"
       },
       {
           "rel": "next",
           "uri": "http:\/\/localhost:8000\/notes?offset=10"
       }
   ]
}
```

Integrating a Template Engine
=============================

The free-form nature of Commando can make it seem difficult to get started building a
full-featured web app. See how easy it is to integrate Commando with the powerful
[Twig Template Engine](http://twig.sensiolabs.org/).

We start by making a new `Action` interface similar to `RequestHandler`,
but returns a `View` object instead of a `Response`. The `View` object just holds a view name
and template parameters.

```php
class View
{
    private $name;
    private $context;

    public function __construct($name, array $context = [])
    {
        $this->name = $name;
        $this->context = $context;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getContext()
    {
        return $this->context;
    }
}
```

```php
interface Action
{
    /**
     * @param Request $request
     * @return View
     */
    public function handle(Request $request);
}
```

```php
class HomeAction implements Action
{
    public function handle(Request $request)
    {
        $name = $request->query->get('name', 'Anonymous Coward');

        return new View('home', ['name' => $name]);
    }
}
```

Now we need a Commando `RequestHandler` that delegate `Requests` to `Action` instances
and converts the returned `View` object into a HTTP `Response` object using Twig.

```php
class TemplatedRequestHandler implements \Commando\Web\RequestHandler
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
        $this->action = new HomeAction();
    }

    public function handle(Request $request)
    {
        $view = $this->action->handle($request);
        $content = $this->twig->render($view->getName(), $view->getContext());

        return new Response($content, 200);
    }
}
```

Run the full example under `sample-twig`:

```bash
cd sample-twig/ && ./start.sh
```


Asynchronous Web Component Handling
===================================

Web components handle their own business logic and rendering, giving you highly decoupled,
pluggable units for your website.

Handlers are very easy to componentize. You can think of a component as an object that
renders it's own HTML content when invoked by a coordinating object.

For this example, we are going to create a master RequestHandler that asynchronously
calls components to render, then assembles component content into a complete page.

```php
namespace AsyncSample;

use Commando\Web\Request;
use Commando\Web\RequestHandler;
use Commando\Web\Response;
use Amp;
use Mustache_Engine;

class Application extends \Commando\Application implements RequestHandler
{
    /**
     * @var Component[] async components making up application
     */
    private $components;

    public function __construct()
    {
        parent::__construct();
        $this->setWebRequestHandler($this);
        $this->components = [
            new Component('component1', 'Component 1'),
            new Component('component2', 'Component 2'),
            new Component('component3', 'Component 3'),
            new Component('component4', 'Component 4'),
            new Component('component5', 'Component 5'),
            new Component('component6', 'Component 6'),
        ];
    }

    public function handle(Request $request)
    {
        $contentMap = [];
        Amp\run(function() use ($request, &$contentMap) {
            $promises = [];
            foreach ($this->components as $component) {
                $name = $component->getName();
                $promises[$name] = $component->getContentPromise($request);
            }
            $contentMapPromise = Amp\all($promises);
            $contentMap = Amp\wait($contentMapPromise);
            Amp\stop();
        });

        $mustache = new Mustache_Engine();
        $template = file_get_contents(dirname(__DIR__) . '/views/layout.mustache');
        $content = $mustache->render($template, $contentMap);

        return new Response($content);
    }
}
```

The first thing we do in `RequestHandler::handle()` is start an event reactor loop using `Amp`
to manage the asynchronous processes. The `Component` returns a `Promise` for some HTML content
that can be cashed in on when all the `Components` have finished generating content.

```php
namespace AsyncSample;

use Amp;
use Amp\Future;
use Commando\Web\Request;
use Mustache_Engine;

class Component
{
    private $name;
    private $title;

    public function __construct($name, $title)
    {
        $this->name = $name;
        $this->title = $title;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getContentPromise(Request $request)
    {
        $future = new Future();
        Amp\immediately(function() use ($future, $request) {
            $start = microtime(true);
            sleep(rand(0, 1)); // blocking

            $mustache = new Mustache_Engine();
            $template = file_get_contents(dirname(__DIR__) . '/views/component.mustache');
            $content = $mustache->render($template, [
                'title' => $this->title,
                'message' => md5(uniqid()),
                'duration' => round((microtime(true) - $start) * 1000, 2) . ' ms'
            ]);

            $future->succeed($content);
        });

        return $future->promise();
    }
}
```

The components in the `Application` are just simple objects, so any dependencies
can be passed through from the the `Application` that composes them.

Run the full example under `sample-async`:

```bash
./sample-async/start.sh
```