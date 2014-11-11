<?php
namespace Commando;

class Application
{
    private $config;

    public function __construct(ApplicationConfig $config)
    {
        $this->config = $config;
    }


}