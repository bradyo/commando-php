<?php
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