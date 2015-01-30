<?php
namespace Commando\Web;

class JsonRequest implements Request
{
    use RequestDecorator;

    private $data;

    public function __construct(Request $request)
    {
        $this->request = $request;

        if (in_array($this->getRequestMethod(), ['POST', 'PUT', 'PATCH'])) {
            // parse request body into data property
            $this->data = json_decode($request->getBody(), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Failed to decode JSON string");
            }
        }
    }

    public function getData()
    {
        return $this->data;
    }
}