<?php

namespace App\Application\User\Handlers;

use App\Application\User\Commands\RegisterUserCommand;
use App\Application\User\ValueObjects\UserId;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\UserInvalidArgumentException;
use App\Domain\User\Repositories\UserRepository;

class RegisterUserHandler
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(RegisterUserCommand $command): User
    {
        if ($this->repository->findUserByEmail($command->email) !== null) {
            throw new UserInvalidArgumentException("User with email " . $command->email->getValue() . "already exists!");
        }

        return $this->repository->register(new User(
            id: UserId::next(),
            name: $command->name,
            email: $command->email,
            password: password_hash($command->password, PASSWORD_BCRYPT)
        ));
    }
}
