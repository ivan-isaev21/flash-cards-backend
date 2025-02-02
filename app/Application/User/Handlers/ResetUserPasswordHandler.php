<?php

namespace App\Application\User\Handlers;

use App\Application\User\Commands\ResetUserPasswordCommand;
use App\Application\User\Contracts\PasswordHasher;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\InvalidVerifyTokenException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Exceptions\UserNotVerifiedException;
use App\Domain\User\Repositories\UserRepository;
use DateTimeImmutable;

class ResetUserPasswordHandler
{
    private UserRepository $repository;
    private PasswordHasher $passwordHasher;

    public function __construct(UserRepository $repository, PasswordHasher $passwordHasher)
    {
        $this->repository = $repository;
        $this->passwordHasher = $passwordHasher;
    }

    public function handle(ResetUserPasswordCommand $command): User
    {
        $user = $this->repository->findUserByEmail($command->email);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        if (!$user->isVerified()) {
            throw new UserNotVerifiedException();
        }

        if (!$user->verifiedToken->equals($command->token) || $user->verifiedToken->isExpired()) {
            throw new InvalidVerifyTokenException();
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
