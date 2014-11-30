<?php
namespace Sample\Rest\Handler;

use Commando\Web\Json\JsonResponse;
use Sample\Security\AuthenticatedRequest;

class DeleteHandler extends AbstractHandler
{
    public function handle(AuthenticatedRequest $request)
    {
        $id = $request->getMatchedRoute()->getParam('id');
        $this->config->getRepository()->remove($id);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Deleted successfully'
        ]);
    }
}