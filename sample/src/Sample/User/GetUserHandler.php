<?php
namespace Sample\User;

use Commando\Web\Json\JsonResponse;
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

        $isAdmin = $request->getAccessToken()->hasRole(Roles::ADMIN);
        $isUser = ($request->getAccessToken()->getUserId() === $id);
        if (! $isAdmin && ! $isUser) {
            return new JsonResponse('Not allowed', 403);
        }

        $user = $this->userRepository->find($id);
        if ($user == null) {
            return new JsonResponse('User not found with Id = ' . $id, 404);
        }

        return new UserResponse($user);
    }
}