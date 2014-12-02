<?php
namespace TwigSample;

use Twig_Loader_Filesystem;
use Twig_Environment;

class Application extends \Commando\Application
{
    public function __construct(array $config)
    {
        parent::__construct();

        $twig = new Twig_Environment(
            new Twig_Loader_Filesystem($config['views_path']),
            ['cache' => $config['cache_path']]
        );

        $this->setWebRequestHandler(new RequestHandler($twig));
    }
}