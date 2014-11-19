<?php
namespace Sample\User;

use Commando\Web\Request;
use Commando\Web\RequestHandler;

class ListUsersHandler implements RequestHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(Request $request)
    {
        $users = $this->userRepository->findAll();

        return new ListResponse($users);
    }
}