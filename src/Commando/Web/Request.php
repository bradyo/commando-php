<?php
namespace Commando\Web;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest
{
    public function fromRoute($name)
    {
        return $this->attributes->get($name);
    }
}