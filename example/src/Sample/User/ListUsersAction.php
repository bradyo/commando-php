<?php
namespace Sample\User;

use Commando\Action;

class ListUsersAction implements Action
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