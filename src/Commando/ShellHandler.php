<?php
namespace Commando;

interface ShellHandler
{
    /**
     * @param array $args
     */
    public function handle(array $args);
}