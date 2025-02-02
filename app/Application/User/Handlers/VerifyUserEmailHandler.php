<?php

namespace App\Application\User\Handlers;

use App\Application\User\Commands\VerifyUserEmailCommand;
use App\Application\User\ValueObjects\Token;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\InvalidVerifyTokenException;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Exceptions\VerifyTokenExpiredException;
use App\Domain\User\Repositories\UserRepository;
use DateTimeImmutable;

class VerifyUserEmailHandler
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository,)
    {
        $this->repository = $repository;
    }

    public function handle(VerifyUserEmailCommand $command): User
    {
        $user = $this->repository->findUserById($command->id);

        if ($user === null) {
            throw new UserNotFoundException($command->id);
        }

        if ($user->verifiedToken === null || $user->verifiedToken->isExpired()) {
            throw new VerifyTokenExpiredException();
        }

        $requestVerifyToken = new Token(
            value: $command->token,
            type: 'verify-token',
            createdAt: new DateTimeImmutable()
        );

        if (!$user->verifiedToken->equals($requestVerifyToken)) {
            throw new InvalidVerifyTokenException();
        }

        $updatedUser = new User(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            password: $user->password,
            emailVerifiedAt: new DateTimeImmutable(),
            verifiedToken: null,
            createdAt: $user->createdAt,
            updatedAt: new DateTimeImmutable()
        );

        return $this->repository->save($updatedUser);
    }
}
