<?php
namespace SampleApi\Core;

use Commando\Web\JsonResponse;

class ValidationErrorResponse extends JsonResponse
{
    /**
     * @param string $message
     * @param ValidationError[] $errors
     */
    public function __construct($message, array $errors)
    {
        $content = [
            'status' => 'error',
            'message' => $message,
        ];
        $errorData = [];
        foreach ($errors as $error) {
            $errorData[] = [
                'name' => $error->getName(),
                'message' => $error->getMessage()
            ];
        }
        if (count($errorData) > 0) {
            $content['errors'] = $errorData;
        }
        parent::__construct($content, 400);
    }
}