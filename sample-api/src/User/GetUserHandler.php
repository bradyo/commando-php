<?php
namespace SampleApi\User;

use Commando\Web\MatchedRoute;
use SampleApi\Core\NotFoundResponse;
use SampleApi\Security\AuthenticatedRequest;
use SampleApi\Security\AuthenticatedRequestHandler;
use SampleApi\Security\Roles;

class GetUserHandler implements AuthenticatedRequestHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(AuthenticatedRequest $request, MatchedRoute $matchedRoute)
    {
        $id = $matchedRoute->getParam('id');
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