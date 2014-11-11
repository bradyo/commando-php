<?php
namespace Sample\User;

use Commando\RequestHandler;

class PostUserHandler implements RequestHandler
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
        $errors = $this->userPostValidator->validate($userPost);
        if (count($errors) > 0) {
            return new InvalidResponse($errors);
        }

        $savedUser = $this->userService->registerUser($userPost);

        return new UserPostOkResponse($savedUser, $request);
    }
}