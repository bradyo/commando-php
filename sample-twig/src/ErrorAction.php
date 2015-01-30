<?php
namespace SampleTwig;

use Commando\Web\Request;
use Exception;

class ErrorAction implements Action
{
    public function handle(Request $request)
    {
        throw new Exception('Something terrible happened');
    }
}