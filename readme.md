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

Browse the sample application under `sample/` folder.

Commando uses Handlers to processes requests from either the web or command line.

To implement a web RequestHandler, you need to create a Handler class and
register a Route with the application.

```php
namespace Sample;

use Commando\Web\Json\JsonResponse;
use Commando\Web\Request;
use Commando\Web\RequestHandler;

class GetHomeHandler implements RequestHandler
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
        $route = new Route(RequestMethod::GET, '/', new GetHomeHandler());
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

```sh
php -S localhost:8000
```

Here's a taste of what you can do with Commando Handlers:

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

    public function __construct(UserPostValidator $userFormValidator, UserService $userService)
    {
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
