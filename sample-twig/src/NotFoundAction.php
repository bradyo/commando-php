<?php
namespace SampleTwig;

use Commando\Web\Request;

class NotFoundAction implements Action
{
    public function handle(Request $request)
    {
        return new View('not-found');
    }
}