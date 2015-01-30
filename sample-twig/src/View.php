<?php
namespace SampleTwig;

class View
{
    private $name;
    private $context;

    public function __construct($name, array $context = [])
    {
        $this->name = $name;
        $this->context = $context;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getContext()
    {
        return $this->context;
    }
}