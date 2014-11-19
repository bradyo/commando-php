<?php
namespace Commando\Shell;

class DefaultShellHandler implements ShellHandler
{
    public function handle(array $params)
    {
        echo "Commando Application Shell\n";
    }
}