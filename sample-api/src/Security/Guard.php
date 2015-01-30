<?php
namespace SampleApi\Security;

use Commando\Web\Request;

class Guard
{
    public function authenticate(Request $request)
    {
        $authHeader = $request->getHeader('Authorization');
        if ($authHeader && preg_match('/^Basic (.+)/', $authHeader, $matches)) {
            list($user, $password) = explode(':', base64_decode($matches[1]));
            if ($user === 'admin' && $password === 'password') {
                return new AccessToken(1, [Roles::ADMIN]);
            }
        }

        return new AccessToken(null, [Roles::GUEST]);
    }
}