<?php
namespace Sample\Security;

use Commando\Web\Request;

class Guard
{
    public function authenticate(Request $request)
    {
        $accessToken = new AccessToken(null, [Roles::GUEST]);
        if ($request->getUser() == 'admin' && $request->getPassword() === 'password') {
            $accessToken = new AccessToken(1, [Roles::ADMIN]);
        }
        return new AuthenticatedRequest($request, $accessToken);
    }
}