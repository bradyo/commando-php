<?php
namespace SampleApi\Core;

use Commando\Web\JsonResponse;

class ErrorResponse extends JsonResponse
{
    /**
     * @param string $message
     */
    public function __construct($message)
    {
        $content = [
            'status' => 'error',
            'message' => $message,
        ];
        parent::__construct($content, 500);
    }
}