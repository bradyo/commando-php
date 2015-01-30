<?php
namespace SampleApi\User;

use Commando\Web\MatchedRoute;
use SampleApi\Core\NotAllowedResponse;
use SampleApi\Security\AuthenticatedRequest;
use SampleApi\Security\AuthenticatedRequestHandler;
use SampleApi\Security\Roles;

class ListUsersHandler implements AuthenticatedRequestHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(AuthenticatedRequest $request, MatchedRoute $matchedRoute)
    {
        $isAdmin = $request->getAccessToken()->hasRole(Roles::ADMIN);
        if (! $isAdmin) {
            return new NotAllowedResponse('Not allowed to list Users');
        }

        $users = $this->userRepository->findAll();

        return new UserListResponse($users);
    }
}