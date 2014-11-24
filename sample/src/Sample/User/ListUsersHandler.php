<?php
namespace Sample\User;

use Sample\Core\NotAllowedResponse;
use Sample\Security\AuthenticatedRequest;
use Sample\Security\AuthenticatedRequestHandler;
use Sample\Security\Roles;

class ListUsersHandler implements AuthenticatedRequestHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(AuthenticatedRequest $request)
    {
        $isAdmin = $request->getAccessToken()->hasRole(Roles::ADMIN);
        if (! $isAdmin) {
            return new NotAllowedResponse('Not allowed to list Users');
        }

        $users = $this->userRepository->findAll();

        return new UserListResponse($users);
    }
}