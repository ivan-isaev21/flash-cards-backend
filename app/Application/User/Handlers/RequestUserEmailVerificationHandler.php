<?php

namespace App\Application\User\Handlers;

use App\Application\User\Commands\RequestUserEmailVerificationCommand;
use App\Application\User\Contracts\VerifyTokenGenerator;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\TooManyAttemptsRequestVerifyUserEmailException;
use App\Domain\User\Exceptions\UserNotFoundException;
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

    public function handle(RequestUserEmailVerificationCommand $command): User
    {
        $user = $this->repository->findUserById($command->id);

        if ($user === null) {
            throw new UserNotFoundException($command->id);
        }

        $nextAttempt = $user->verifiedToken->getCreatedAt()->modify("+1 hour");

        if ($user->verifiedToken !== null && $nextAttempt >= new DateTimeImmutable()) {
            throw new TooManyAttemptsRequestVerifyUserEmailException();
        }

        $newVerifyToken = $this->verifyTokenGenerator->generate();

        return $this->repository->requestEmailVerification(id: $command->id, verifyToken: $newVerifyToken);
    }
}
