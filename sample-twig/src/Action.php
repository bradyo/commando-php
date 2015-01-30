<?php
namespace SampleTwig;

use Commando\Web\Request;

interface Action
{
    /**
     * @param Request $request
     * @return View
     */
    public function handle(Request $request);
}