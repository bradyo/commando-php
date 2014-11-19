<?php
namespace Sample;

use Commando\Application as BaseApplication;
use Sample\Core\CoreModule;
use Sample\User\UserModule;

class Application extends BaseApplication
{
    public function __construct($configPath)
    {
        parent::__construct($configPath);
        $this->setModule('core', new CoreModule($this->getConfig()));
    }

    /**
     * @return CoreModule
     */
    public function getCoreModule()
    {
        return $this->getModule('core');
    }
}