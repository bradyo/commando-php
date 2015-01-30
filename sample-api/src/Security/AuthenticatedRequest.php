<?php
namespace SampleApi\Security;

use Commando\Web\JsonRequest;
use Commando\Web\Request;

class AuthenticatedRequest extends JsonRequest
{
    private $accessToken;

    public function __construct(Request $request, AccessToken $accessToken)
    {
        parent::__construct($request);
        $this->accessToken = $accessToken;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }
}