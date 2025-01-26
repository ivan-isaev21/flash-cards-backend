<?php

namespace App\Domain\User\Services;

use App\Application\User\Commands\ChangeUserPasswordCommand;
use App\Application\User\Commands\RegisterUserCommand;
use App\Application\User\Handlers\ChangeUserPasswordHandler;
use App\Application\User\Handlers\RegisterUserHandler;
use App\Domain\User\Entities\User;
use App\Domain\User\Events\UserPasswordChanged;
use App\Domain\User\Events\UserRegistered;
use Illuminate\Contracts\Events\Dispatcher;

class AuthService
{
    private Dispatcher $dispatcher;
    private RegisterUserHandler $registerUserHandler;
    private ChangeUserPasswordHandler $changeUserPasswordHandler;

    public function __construct(
        Dispatcher $dispatcher,
        RegisterUserHandler $registerUserHandler,
        ChangeUserPasswordHandler $changeUserPasswordHandler
    ) {
        $this->dispatcher = $dispatcher;
        $this->registerUserHandler = $registerUserHandler;
        $this->changeUserPasswordHandler = $changeUserPasswordHandler;
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
}
