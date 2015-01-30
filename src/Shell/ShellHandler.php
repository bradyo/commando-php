<?php
namespace Commando\Shell;

interface ShellHandler
{
    /**
     * @param Command $command
     */
    public function handle(Command $command);
}