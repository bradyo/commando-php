<?php
namespace SampleApi\Core;

use Commando\Web\JsonResponse;

class NotAllowedResponse extends JsonResponse
{
    public function __construct($message)
    {
        $data = [
            'status' => 'error',
            'message' => $message
        ];
        parent::__construct($data, 403);
    }
}