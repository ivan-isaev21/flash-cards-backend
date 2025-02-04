<?php

namespace App\Application\User\Handlers;

use App\Application\User\Commands\LogoutUserCommand;
use App\Application\User\ValueObjects\UserId;
use App\Domain\User\Contracts\LoginTokenGenerator;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Repositories\UserRepository;

class LogoutUserHandler
{
    private UserRepository $repository;
    private LoginTokenGenerator $tokenGenerator;

    public function __construct(UserRepository $repository, LoginTokenGenerator $tokenGenerator)
    {
        $this->repository = $repository;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function handle(LogoutUserCommand $command): bool
    {
        $user = $this->repository->findUserById($command->id);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        $this->tokenGenerator->delete(id: $command->id, token: $command->token);
        return true;
    }
}
