<?php
namespace Commando\Web;

class JsonResponse extends TextResponse
{
    private $data;

    public function __construct($data = '', $statusCode = 200, $headers = [])
    {
        parent::__construct(json_encode($data, JSON_PRETTY_PRINT) . "\n", $statusCode, $headers);
        $this->data = $data;
    }

    public function getHeaders()
    {
        return array_merge(
            parent::getHeaders(),
            ['Content-Type' => 'application/json']
        );
    }

    public function getData()
    {
        return $this->data;
    }
}