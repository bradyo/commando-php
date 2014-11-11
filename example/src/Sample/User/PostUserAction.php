<?php
namespace Sample\User;

use Commando\Action;

class PostUserAction implements Action
{
    private $userPostValidator;
    private $userService;

    public function __construct(UserPostValidator $userFormValidator, UserService $userService)
    {
        $this->userPostValidator = $userFormValidator;
        $this->userService = $userService;
    }

    public function handle(Request $request)
    {
        $userPost = new UserPost($request);
        $errorMessages = $this->userPostValidator->validate($userPost);
        if (count($errorMessages) > 0) {
            return new InvalidResponse($errorMessages);
        }

        $savedUser = $this->userService->registerUser($userPost);

        return new UserPostOkResponse($savedUser, $request);
    }
}