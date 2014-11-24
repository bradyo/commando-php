<?php
namespace Sample\User;

use Commando\Web\Json\JsonResponse;
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
            return new JsonResponse('Not allowed', 403);
        }

        $users = $this->userRepository->findAll();

        return new UserListResponse($users);
    }
}