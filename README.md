Commando PHP Framework
======================

The Commando PHP Framework is minimalist framework that provides only the essentials
for building applications for the web and command line.

It deviates from the ubiquitous MVC-style frameworks by providing high-level interfaces
and modular structure for HTTP and Shell requests and leaving most other decisions
up to the developer.

Commando has no View, Model, or DI layers. Those choices are all up to the developer and
can vary between modules if needed.

Commando:
- provides RequestHandler and ShellHandler interfaces
- constructs and application in a compositional way using configs and Module objects,
  an Application is just a unit to hold config and Modules and execute commands
- provides web and shell error handlers

Principles:
- conventional Controller classes replaced by Handler classes, which do only one thing
- provide simple interfaces to implement
- favor composition over inheritance
- favors modular code using composition, not conventions
- provide basic building blocks for developers to extend
- agnostic with respect to Domain Model, View, DI, Validation, Logging, and configuration


Getting Started
===============

Commando uses Handlers to processes requests from either the web or command line.

To implement a web `RequestHandler`, you need to create a Handler class and
register a `Route` with the application.

```php
namespace Sample;

use Commando\Web\Json\JsonResponse;
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

use Commando\Application as CommandoApplication;

class Application extends CommandoApplication
{
    public function __construct($configPath)
    {
        parent::__construct($configPath);
        $route = new Route(Method::GET, '/', new RootHandler());
        $this->addRoute('home', $route);
    }
}

```

Make a script to create and execute your application in your web server folder:

```php
require_once(__DIR__ . '/bootstrap.php');
$config = require(__DIR__ . '/config/config.php');
$app = new \Sample\Application($config);
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

class GuardedRequestHandler implements RequestHandler
{
    private $guard;
    private $securedHandler;

    public function __construct(
        Guard $guard,
        AuthenticatedRequestHandler $securedHandler
    ) {
        $this->guard = $guard;
        $this->securedHandler = $securedHandler;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        if ($request->getUserInfo() !== null) {
            $authenticatedRequest = $this->guard->authenticate($request);
            return $this->securedHandler->handle($authenticatedRequest);
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
        $this->addRoute('list',   Method::GET,    $path,         $listHandler);
        $this->addRoute('post',   Method::POST,   $path,         $postHandler);
        $this->addRoute('get',    Method::GET,    $path.'/{id}', $getHandler);
        $this->addRoute('put',    Method::PUT,    $path.'/{id}', $putHandler);
        $this->addRoute('delete', Method::DELETE, $path.'/{id}', $deleteHandler);
    }

    public function handle(Request $request)
    {
        $handler = $this->router->getHandler($request);
        return $handler->handle($request);
    }
}
```

Building such a compositional interface takes away all the hard work of
creating individual `RequestHandler` classes for each HTTP method, we need only
create a `RestHandler` with the required arguments. An example of constructing
such a request handler would look something like the following:

```php
// create a RestHandler for resources
$db = new PDO('sqlite:db.sqlite');
$userResourceConfig = new UserResourceConfig(
    new UserRepository($db)
);
$noteResourceConfig = new NoteResourceConfig(
    new NoteRepository($db)
);
$repository = new ResourceRepository([
    $userResourceConfig,
    $noteResourceConfig
]);
$noteHandler = new RestRequestHandler($repository, $noteResourceConfig);

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

        $this->addRoute('users',    Method::ANY, '/users[.*]',    $userHandler);
        $this->addRoute('notes',    Method::ANY, '/notes[.*]',    $noteHandler);
        $this->addRoute('articles', Method::ANY, '/articles[.*]', $articleHandler);
    }

    public function handle(Request $request)
    {
        $handler = $this->router->getHandler($request);
        return $handler->handle($request);
    }
}
```


Running the Sample Application
==============================

Browse the sample application in the `sample` folder for more examples.

Start the application on a local port:

```sh
cd ./commando-php/sample/public/
php -S localhost:8000
```

```
curl -H 'Content-type: application/json' -X GET localhost:8000/users/1
{
    "status": "error",
    "message": "Authentication required"
}
```

```
curl --user admin:password -H 'Content-type: application/json' \
-X POST -d '{"email":"admin@domain.com","password":"12324234"}' localhost:8000/users
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
curl --user admin:password -H 'Content-type: application/json' localhost:8000/notes?expand=author
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

