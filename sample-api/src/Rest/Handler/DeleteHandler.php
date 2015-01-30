<?php
namespace SampleApi\Rest\Handler;

use Commando\Web\JsonResponse;
use SampleApi\Rest\RestRequest;

class DeleteHandler extends AbstractHandler
{
    public function handle(RestRequest $request)
    {
        $id = $request->getParam('id');
        $this->config->getRepository()->remove($id);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Deleted successfully'
        ]);
    }
}