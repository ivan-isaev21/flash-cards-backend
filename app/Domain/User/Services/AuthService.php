<?php

namespace App\Domain\User\Services;

use App\Application\User\Commands\ChangeUserPasswordCommand;
use App\Application\User\Commands\LoginUserCommand;
use App\Application\User\Commands\RegisterUserCommand;
use App\Application\User\Commands\RequestUserEmailVerificationCommand;
use App\Application\User\Commands\UpdateUserCommand;
use App\Application\User\Commands\VerifyUserEmailCommand;
use App\Application\User\DataTransferObjects\LoginedUserData;
use App\Application\User\Handlers\ChangeUserPasswordHandler;
use App\Application\User\Handlers\LoginUserHandler;
use App\Application\User\Handlers\RegisterUserHandler;
use App\Application\User\Handlers\RequestUserEmailVerificationHandler;
use App\Application\User\Handlers\UpdateUserHandler;
use App\Application\User\Handlers\VerifyUserEmailHandler;
use App\Domain\User\Entities\User;
use App\Domain\User\Events\UserEmailVerificationRequested;
use App\Domain\User\Events\UserEmailVerified;
use App\Domain\User\Events\UserLogined;
use App\Domain\User\Events\UserPasswordChanged;
use App\Domain\User\Events\UserRegistered;
use App\Domain\User\Events\UserUpdated;
use Illuminate\Contracts\Events\Dispatcher;

class AuthService
{
    private Dispatcher $dispatcher;
    private RegisterUserHandler $registerUserHandler;
    private ChangeUserPasswordHandler $changeUserPasswordHandler;
    private UpdateUserHandler $updateUserHandler;
    private LoginUserHandler $loginUserHandler;
    private VerifyUserEmailHandler $verifyUserEmailHandler;
    private RequestUserEmailVerificationHandler $requestUserEmailVerificationHandler;

    public function __construct(
        Dispatcher $dispatcher,
        RegisterUserHandler $registerUserHandler,
        ChangeUserPasswordHandler $changeUserPasswordHandler,
        UpdateUserHandler $updateUserHandler,
        LoginUserHandler $loginUserHandler,
        VerifyUserEmailHandler $verifyUserEmailHandler,
        RequestUserEmailVerificationHandler $requestUserEmailVerificationHandler
    ) {
        $this->dispatcher = $dispatcher;
        $this->registerUserHandler = $registerUserHandler;
        $this->changeUserPasswordHandler = $changeUserPasswordHandler;
        $this->updateUserHandler = $updateUserHandler;
        $this->loginUserHandler = $loginUserHandler;
        $this->verifyUserEmailHandler = $verifyUserEmailHandler;
        $this->requestUserEmailVerificationHandler = $requestUserEmailVerificationHandler;
    }

    public function register(RegisterUserCommand $command): User
    {
        $user = $this->registerUserHandler->handle($command);
        $this->dispatcher->dispatch(new UserRegistered($user->id));
        return $user;
    }

    public function changePassword(ChangeUserPasswordCommand $command): User
    {
        $user = $this->changeUserPasswordHandler->handle($command);
        $this->dispatcher->dispatch(new UserPasswordChanged($user->id));
        return $user;
    }

    public function update(UpdateUserCommand $command): User
    {
        $user = $this->updateUserHandler->handle($command);
        $this->dispatcher->dispatch(new UserUpdated($user->id));
        return $user;
    }

    public function login(LoginUserCommand $command): LoginedUserData
    {
        $loginedUserData = $this->loginUserHandler->handle($command);
        $this->dispatcher->dispatch(new UserLogined($loginedUserData->user->id));
        return $loginedUserData;
    }

    public function requestVerifyEmail(RequestUserEmailVerificationCommand $command): User
    {
        $user = $this->requestUserEmailVerificationHandler->handle($command);
        $this->dispatcher->dispatch(new UserEmailVerificationRequested($user->id));
        return $user;
    }

    public function verifyEmail(VerifyUserEmailCommand $command): User
    {
        $user = $this->verifyUserEmailHandler->handle($command);
        $this->dispatcher->dispatch(new UserEmailVerified($user->id));
        return $user;
    }
}
