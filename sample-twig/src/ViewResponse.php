<?php
namespace SampleTwig;

use Commando\Web\TextResponse;

class ViewResponse extends TextResponse
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