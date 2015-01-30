<?php
namespace Commando\Web;

interface Response
{
    public function getStatusCode();

    public function getHeaders();

    public function getBody();
}
