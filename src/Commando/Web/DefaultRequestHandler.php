<?php
namespace Commando\Web;

use Commando\Web\Json\JsonResponse;

class DefaultRequestHandler implements RequestHandler
{
    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        $data = ['name' => 'Commando Application'];
        return new JsonResponse($data, 200);
    }
}