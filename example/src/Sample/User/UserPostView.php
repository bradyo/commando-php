<?php
namespace Sample\User;

use Commando\ArrayView;

class UserPostOkResponse extends OkResponse
{
    private $user;

    public function __construct(User $user, Request $request)
    {
        $this->user = $user;
        $this->request = $request;
    }


}