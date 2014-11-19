<?php
namespace Commando\Web\Json;

use Commando\Web\Response;

class JsonResponse extends Response
{
    public function __construct($content = '', $status = 200, $headers = array())
    {
        $content = json_encode($content, JSON_PRETTY_PRINT);
        $headers['content-type'] = 'application/json';
        parent::__construct($content, $status, $headers);
    }
}