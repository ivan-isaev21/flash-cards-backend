<?php

namespace App\Application\User\Handlers;

use App\Application\User\Commands\LoginUserCommand;
use App\Application\User\Contracts\PasswordHasher;
use App\Application\User\Contracts\TokenGenerator;
use App\Application\User\DataTransferObjects\LoginedUserData;
use App\Domain\User\Exceptions\UserInvalidCredentialsException;
use App\Domain\User\Repositories\UserRepository;

class LoginUserHandler
{
    private UserRepository $repository;
    private PasswordHasher $passwordHasher;
    private TokenGenerator $tokenGenerator;

    public function __construct(UserRepository $repository, PasswordHasher $passwordHasher, TokenGenerator $tokenGenerator)
    {
        $this->repository = $repository;
        $this->passwordHasher = $passwordHasher;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function handle(LoginUserCommand $command): LoginedUserData
    {
        $user = $this->repository->findUserByEmail($command->email);

        if ($user === null || !$this->passwordHasher->verify(password: $command->password, hash: $user->password)) {
            throw new UserInvalidCredentialsException();
        }

        $token = $this->tokenGenerator->generate($user->id);

        return new LoginedUserData(user: $user, token: $token);
    }
}
