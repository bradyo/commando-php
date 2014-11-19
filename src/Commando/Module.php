<?php
namespace Commando;

abstract class Module
{
    /**
     * @return array assoc array of RequestHandler callbacks indexed by name
     */
    public function getRequestHandlers() {
        return [];
    }

    /**
     * @return array assoc array of Route objects indexed by name
     */
    public function getRoutes() {
        return [];
    }

    /**
     * Callback for bootstrapping application.
     *
     * Here you can get modules from the application and call any available functions on them
     * as needed.
     *
     * @param Application $app application to bootstrap
     */
    public function bootstrap(Application $app)
    {}
}