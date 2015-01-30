<?php
namespace SampleApi\User;

use Commando\Web\MatchedRoute;
use SampleApi\Core\NotAllowedResponse;
use SampleApi\Core\ValidationErrorResponse;
use SampleApi\Security\AuthenticatedRequest;
use SampleApi\Security\AuthenticatedRequestHandler;
use SampleApi\Security\Roles;

class PostUserHandler implements AuthenticatedRequestHandler
{
    private $userPostValidator;
    private $userService;

    public function __construct(UserPostValidator $userFormValidator, UserService $userService)
    {
        $this->userPostValidator = $userFormValidator;
        $this->userService = $userService;
    }

    public function handle(AuthenticatedRequest $request, MatchedRoute $matchedRoute)
    {
        if (! $request->getAccessToken()->hasRole(Roles::ADMIN)) {
            return new NotAllowedResponse('Not allowed to post Users');
        }

        $userPost = new UserPost($request->getData());
        $errors = $this->userPostValidator->validate($userPost);
        if (count($errors) > 0) {
            return new ValidationErrorResponse('Invalid request', $errors);
        }

        $newUser = $this->userService->registerUser($userPost);

        return new UserResponse($newUser, 201);
    }
}