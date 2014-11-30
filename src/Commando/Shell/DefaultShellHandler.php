<?php
namespace Commando\Shell;

class DefaultShellHandler implements ShellHandler
{
    public function handle(Command $command)
    {
        echo "No commands available\n";
    }
}