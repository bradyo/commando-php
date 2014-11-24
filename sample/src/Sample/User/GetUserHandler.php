<?php
namespace Sample\User;

use Sample\Core\NotFoundResponse;
use Sample\Security\AuthenticatedRequest;
use Sample\Security\AuthenticatedRequestHandler;
use Sample\Security\Roles;

class GetUserHandler implements AuthenticatedRequestHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(AuthenticatedRequest $request)
    {
        $id = $request->fromRoute('id');
        $user = $this->userRepository->find($id);

        $isAdmin = $request->getAccessToken()->hasRole(Roles::ADMIN);
        $isUser = ($request->getAccessToken()->getUserId() === $id);
        $allowed = $isAdmin || $isUser;
        if ($user === null || ! $allowed) {
            return new NotFoundResponse('User not found');
        }

        return new UserResponse($user);
    }
}