<?php
namespace TwigSample;

use Commando\Web\Request;

interface Action
{
    /**
     * @param Request $request
     * @return View
     */
    public function handle(Request $request);
}