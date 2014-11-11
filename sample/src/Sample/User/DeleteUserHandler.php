<?php
namespace Sample\User;

use Commando\RequestHandler;

class DeleteUserHandler implements RequestHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle($request)
    {
        if (! $request->getAuth()->hasRole('ADMIN')) {
            return new ForbiddenResponse('Not allowed to delete users');
        }

        $id = $request->getFromRoute('id');
        $user = $this->userRepository->find($id);
        if ($user === null) {
            return new NotFoundResponse('User not found with Id = ' . $id);
        }

        $this->userRepository->delete($user);

        return new OkResponse('User deleted');
    }
}