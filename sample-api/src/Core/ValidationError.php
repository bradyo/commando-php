<?php
namespace SampleApi\Core;

class ValidationError
{
    private $name;
    private $message;

    public function __construct($name, $message)
    {
        $this->name = $name;
        $this->message = $message;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMessage()
    {
        return $this->message;
    }
}