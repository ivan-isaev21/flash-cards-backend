<?php

namespace App\Domain\User\Services;

use App\Application\User\Commands\ChangeUserPasswordCommand;
use App\Application\User\Commands\LoginUserCommand;
use App\Application\User\Commands\LogoutUserCommand;
use App\Application\User\Commands\RegisterUserCommand;
use App\Application\User\Commands\RequestResetUserPasswordCommand;
use App\Application\User\Commands\RequestUserEmailVerificationCommand;
use App\Application\User\Commands\ResetUserPasswordCommand;
use App\Application\User\Commands\UpdateUserCommand;
use App\Application\User\Commands\VerifyUserEmailCommand;
use App\Application\User\DataTransferObjects\LoginedUserData;
use App\Application\User\Handlers\ChangeUserPasswordHandler;
use App\Application\User\Handlers\GetUserHandler;
use App\Application\User\Handlers\LoginUserHandler;
use App\Application\User\Handlers\LogoutUserHandler;
use App\Application\User\Handlers\RegisterUserHandler;
use App\Application\User\Handlers\RequestResetUserPasswordHandler;
use App\Application\User\Handlers\RequestUserEmailVerificationHandler;
use App\Application\User\Handlers\ResetUserPasswordHandler;
use App\Application\User\Handlers\UpdateUserHandler;
use App\Application\User\Handlers\VerifyUserEmailHandler;
use App\Application\User\Queries\GetUserQuery;
use App\Domain\User\Entities\User;
use App\Domain\User\Events\UserEmailVerificationRequested;
use App\Domain\User\Events\UserEmailVerified;
use App\Domain\User\Events\UserLogined;
use App\Domain\User\Events\UserPasswordChanged;
use App\Domain\User\Events\UserPasswordReseted;
use App\Domain\User\Events\UserPasswordResetRequested;
use App\Domain\User\Events\UserRegistered;
use App\Domain\User\Events\UserUpdated;
use Illuminate\Contracts\Events\Dispatcher;

class UserService
{
    private Dispatcher $dispatcher;
    private RegisterUserHandler $registerUserHandler;
    private ChangeUserPasswordHandler $changeUserPasswordHandler;
    private UpdateUserHandler $updateUserHandler;
    private LoginUserHandler $loginUserHandler;
    private VerifyUserEmailHandler $verifyUserEmailHandler;
    private RequestUserEmailVerificationHandler $requestUserEmailVerificationHandler;
    private RequestResetUserPasswordHandler $requestResetUserPasswordHandler;
    private ResetUserPasswordHandler $resetUserPasswordHandler;
    private GetUserHandler $getUserHandler;
    private LogoutUserHandler $logoutUserHandler;

    public function __construct(
        Dispatcher $dispatcher,
        RegisterUserHandler $registerUserHandler,
        ChangeUserPasswordHandler $changeUserPasswordHandler,
        UpdateUserHandler $updateUserHandler,
        LoginUserHandler $loginUserHandler,
        VerifyUserEmailHandler $verifyUserEmailHandler,
        RequestUserEmailVerificationHandler $requestUserEmailVerificationHandler,
        RequestResetUserPasswordHandler $requestResetUserPasswordHandler,
        ResetUserPasswordHandler $resetUserPasswordHandler,
        GetUserHandler $getUserHandler,
        LogoutUserHandler $logoutUserHandler
    ) {
        $this->dispatcher = $dispatcher;
        $this->registerUserHandler = $registerUserHandler;
        $this->changeUserPasswordHandler = $changeUserPasswordHandler;
        $this->updateUserHandler = $updateUserHandler;
        $this->loginUserHandler = $loginUserHandler;
        $this->verifyUserEmailHandler = $verifyUserEmailHandler;
        $this->requestUserEmailVerificationHandler = $requestUserEmailVerificationHandler;
        $this->requestResetUserPasswordHandler = $requestResetUserPasswordHandler;
        $this->resetUserPasswordHandler = $resetUserPasswordHandler;
        $this->getUserHandler = $getUserHandler;
        $this->logoutUserHandler = $logoutUserHandler;
    }

    public function show(GetUserQuery $query): User
    {
        return $this->getUserHandler->handle($query);
    }

    public function register(RegisterUserCommand $command): User
    {
        $user = $this->registerUserHandler->handle($command);
        $this->dispatcher->dispatch(new UserRegistered($user->id));
        return $user;
    }

    public function requestResetPassword(RequestResetUserPasswordCommand $command): User
    {
        $user = $this->requestResetUserPasswordHandler->handle($command);
        $this->dispatcher->dispatch(new UserPasswordResetRequested($user->id));
        return $user;
    }

    public function resetPassword(ResetUserPasswordCommand $command): User
    {
        $user = $this->resetUserPasswordHandler->handle($command);
        $this->dispatcher->dispatch(new UserPasswordReseted($user->id));
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

    public function logout(LogoutUserCommand $command): bool
    {
       return $this->logoutUserHandler->handle($command);
    }

    public function requestEmailVerification(RequestUserEmailVerificationCommand $command): bool
    {
        $user = $this->requestUserEmailVerificationHandler->handle($command);

        if ($user != null) {
            $this->dispatcher->dispatch(new UserEmailVerificationRequested($user->id));
            return true;
        }

        return false;
    }

    public function verifyEmail(VerifyUserEmailCommand $command): User
    {
        $user = $this->verifyUserEmailHandler->handle($command);
        $this->dispatcher->dispatch(new UserEmailVerified($user->id));
        return $user;
    }
}
