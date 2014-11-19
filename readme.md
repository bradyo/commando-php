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
- favors modular code with composition, not conventions
- provide basic building blocks for developers to build off
- agnostic with respect to Domain Model, View, DI, Logging, and configuration


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
        $this->addRoute('home', new Route(RequestMethod::GET, '/', new GetHomeHandler()));
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