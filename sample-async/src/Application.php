<?php
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