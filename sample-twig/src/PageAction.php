<?php
namespace SampleTwig;

use Commando\Web\Request;

class PageAction implements Action
{
    public function handle(Request $request)
    {
        return new View('page');
    }
}