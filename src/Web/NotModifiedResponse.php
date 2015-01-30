<?php
namespace Commando\Web;

class NotModifiedResponse extends TextResponse
{
    public function __construct()
    {
        parent::__construct(null, 304);
    }
}
