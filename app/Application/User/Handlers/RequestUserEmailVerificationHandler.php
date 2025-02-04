<?php

namespace App\Application\User\Handlers;

use App\Application\User\Commands\RequestUserEmailVerificationCommand;
use App\Domain\User\Contracts\VerifyTokenGenerator;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\TooManyAttemptsRequestVerifyUserEmailException;
use App\Domain\User\Repositories\UserRepository;
use DateTimeImmutable;

class RequestUserEmailVerificationHandler
{
    private UserRepository $repository;
    private VerifyTokenGenerator $verifyTokenGenerator;

    public function __construct(UserRepository $repository, VerifyTokenGenerator $verifyTokenGenerator)
    {
        $this->repository = $repository;
        $this->verifyTokenGenerator = $verifyTokenGenerator;
    }

    public function handle(RequestUserEmailVerificationCommand $command): ?User
    {
        $user = $this->repository->findUserByEmail($command->email);

        if ($user === null) {
            return null;
        }

        if ($user->verifiedToken !== null) {
            $nextAttempt = $user->verifiedToken->getCreatedAt()->modify("+1 hour");

            if ($nextAttempt >= new DateTimeImmutable()) {
                throw new TooManyAttemptsRequestVerifyUserEmailException();
            }
        }

        $newVerifyToken = $this->verifyTokenGenerator->generate();

        $updatedUser = new User(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            password: $user->password,
            emailVerifiedAt: $user->emailVerifiedAt,
            verifiedToken: $newVerifyToken,
            createdAt: $user->createdAt,
            updatedAt: new DateTimeImmutable()
        );

        return $this->repository->save($updatedUser);
    }
}
