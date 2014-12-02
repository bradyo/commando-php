<?php
namespace TwigSample;

use Commando\Web\Response;

class ViewResponse extends Response
{
    public function __construct(\Twig_Environment $twig, View $view, $httpStatus = 200)
    {
        $html = $twig->render(
            $view->getName() . '.twig',
            $view->getContext()
        );
        parent::__construct($html, $httpStatus);
    }
}