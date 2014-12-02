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

Run the full example:

```bash
sh /sample-twig/start.sh
```