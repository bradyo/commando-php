<?php
namespace Commando\Web;

interface Request
{
    public function getRemoteIpAddress();

    public function getRequestMethod();

    public function getScheme();

    public function getServerName();

    public function getPort();

    public function getUri();

    public function getQueryString();

    public function getHeaders();

    public function getHeader($name);

    public function getBody();
}