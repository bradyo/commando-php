<?php
namespace Sample\Security;

use Commando\Web\Request;

class AuthenticatedRequest extends Request
{
    private $accessToken;

    public function __construct(Request $request, AccessToken $accessToken)
    {
        parent::__construct(
            $request->query->all(),
            $request->request->all(),
            $request->attributes->all(),
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all(),
            $request->content
        );
        $this->accessToken = $accessToken;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }
}