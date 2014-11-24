<?php
namespace Commando;

interface Module
{
    /**
     * Callback for bootstrapping application.
     *
     * Here you can get modules from the application and call any available functions on them
     * as needed to glue your application together.
     *
     * @param Application $application
     */
    public function bootstrap(Application $application);
}