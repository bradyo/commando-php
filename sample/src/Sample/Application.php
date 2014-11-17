<?php
namespace Sample;

use Commando\Application as BaseApplication;
use Sample\Core\CoreModule;
use Sample\Note\NoteModule;
use Sample\User\UserModule;

class Application extends BaseApplication
{
    private $userModule;
    private $noteModule;

    public function __construct(array $config) {
        parent::__construct($config);
//        $this->coreModule = new CoreModule($config);
//        $this->userModule = new UserModule($this->coreModule);
//        $this->noteModule = new NoteModule($this->coreModule);
    }

    public function getCoreModule()
    {
        return $this->coreModule;
    }

    public function getUserModule()
    {
        return $this->userModule;
    }

    public function getNoteModule()
    {
        return $this->noteModule;
    }
}