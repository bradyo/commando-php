<?php
namespace Sample\User;

use Commando\Action;
use Commando\JsonResponse;

class GetUserAction implements Action
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

        return new OkResponse(new UserView($user));
    }
}