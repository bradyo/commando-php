<?php
namespace Sample\User;

use Commando\Web\Request;
use Commando\Web\RequestHandler;

class GetUserHandler implements RequestHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(Request $request)
    {
        $id = $request->getFromRoute('id');
        $user = $this->userRepository->findOneById($id);
        if ($user === null) {
            return new NotFoundResponse('User not found with Id = ' . $id);
        }

        return new UserResponse($user);
    }
}