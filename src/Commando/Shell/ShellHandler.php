<?php
namespace Commando\Shell;

interface ShellHandler
{
    /**
     * @param array $args
     */
    public function handle(array $args);
}