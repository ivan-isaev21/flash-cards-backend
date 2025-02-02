<?php

namespace App\Application\User\Handlers;

use App\Application\User\Commands\ChangeUserPasswordCommand;
use App\Domain\User\Contracts\PasswordHasher;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Exceptions\UserNotVerifiedException;
use App\Domain\User\Repositories\UserRepository;
use DateTimeImmutable;

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

        if (!$user->isVerified()) {
            throw new UserNotVerifiedException();
        }

        $updatedUser = new User(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            password: $this->passwordHasher->make($command->password),
            emailVerifiedAt: $user->emailVerifiedAt,
            verifiedToken: null,
            createdAt: $user->createdAt,
            updatedAt: new DateTimeImmutable()
        );

        return $this->repository->save($updatedUser);
    }
}
