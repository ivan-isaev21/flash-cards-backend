<?php

namespace App\Application\User\Handlers;

use App\Application\User\Commands\ChangeUserPasswordCommand;
use App\Application\User\Contracts\PasswordHasher;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Repositories\UserRepository;

class ChangeUserPasswordHandler
{
    private UserRepository $repository;
    private PasswordHasher $passwordHasher;

    public function __construct(UserRepository $repository, PasswordHasher $passwordHasher)
    {
        $this->repository = $repository;
        $this->passwordHasher = $passwordHasher;
    }

    public function handle(ChangeUserPasswordCommand $command): User
    {
        $user = $this->repository->findUserById($command->id);

        if ($user === null) {
            throw new UserNotFoundException($command->id);
        }

        return $this->repository->changePassword(
            id: $user->id,
            password: $this->passwordHasher->make($command->password)
        );
    }
}
