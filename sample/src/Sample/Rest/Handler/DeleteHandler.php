<?php
namespace Sample\Rest\Handler;

use Commando\Web\Json\JsonResponse;
use Commando\Web\Request;

class DeleteHandler extends AbstractHandler
{
    public function handle(Request $request)
    {
        $id = $request->fromRoute('id');
        $this->config->getRepository()->remove($id);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Deleted successfully'
        ]);
    }
}