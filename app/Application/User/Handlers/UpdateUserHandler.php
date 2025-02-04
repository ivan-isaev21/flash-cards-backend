<?php

namespace App\Application\User\Handlers;

use App\Application\User\Commands\UpdateUserCommand;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\UserInvalidArgumentException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Exceptions\UserNotVerifiedException;
use App\Domain\User\Repositories\UserRepository;
use DateTimeImmutable;

class UpdateUserHandler
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(UpdateUserCommand $command): User
    {
        $user = $this->repository->findUserById($command->id);

        if ($user === null) {
            throw new UserNotFoundException($command->id);
        }

        if (!$user->isVerified()) {
            throw new UserNotVerifiedException();
        }

        $emailUser = $this->repository->findUserByEmail($command->email);

        if ($emailUser !== null && !$emailUser->id->equals($user->id)) {
            throw new UserInvalidArgumentException("User with email " . $command->email->getValue() . " already exists!");
        }

        $updatedUser = new User(
            id: $user->id,
            name: $command->name,
            email: $command->email,
            password: $user->password,
            emailVerifiedAt: $user->emailVerifiedAt,
            verifiedToken: null,
            createdAt: $user->createdAt,
            updatedAt: new DateTimeImmutable()
        );

        return $this->repository->save($updatedUser);
    }
}
