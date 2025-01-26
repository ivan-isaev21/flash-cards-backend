<?php

namespace App\Application\User\Handlers;

use App\Application\User\Commands\ChangeUserPasswordCommand;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Repositories\UserRepository;

class ChangeUserPasswordHandler
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ChangeUserPasswordCommand $command): User
    {
        $user = $this->repository->findUserById($command->id);

        if ($user === null) {
            throw new UserNotFoundException($command->id);
        }

        return $this->repository->changePassword(
            id: $user->id,
            password: password_hash($command->password, PASSWORD_BCRYPT)
        );
    }
}
