<?php
namespace Commando;

class Application
{
    private $config;

    /**
     * @var Module[]
     */
    private $modules;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function bootstrap()
    {
        foreach ($this->modules as $module) {
            $module->bootstrap();
        }
    }

    public function run() {

    }

    public function handle(Request $request)
    {

        return new OkResponse();
    }
}