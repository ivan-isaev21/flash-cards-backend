<?php

namespace Tests\Unit\Domain\User\Services;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\Commands\ChangeUserPasswordCommand;
use App\Application\User\Commands\RegisterUserCommand;
use App\Application\User\Handlers\ChangeUserPasswordHandler;
use App\Application\User\Handlers\RegisterUserHandler;
use App\Application\User\ValueObjects\UserId;
use App\Domain\User\Entities\User;
use App\Domain\User\Events\UserPasswordChanged;
use App\Domain\User\Events\UserRegistered;
use App\Domain\User\Services\AuthService;
use PHPUnit\Framework\TestCase;
use Illuminate\Contracts\Events\Dispatcher;
use Mockery;

class AuthServiceTest extends TestCase
{
    private Dispatcher $dispatcher;
    private RegisterUserHandler $registerUserHandler;
    private ChangeUserPasswordHandler $changeUserPasswordHandler;
    private AuthService $service;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dispatcher = Mockery::mock(Dispatcher::class);
        $this->registerUserHandler = Mockery::mock(RegisterUserHandler::class);
        $this->changeUserPasswordHandler = Mockery::mock(ChangeUserPasswordHandler::class);
        $this->service = new AuthService(
            dispatcher: $this->dispatcher,
            registerUserHandler: $this->registerUserHandler,
            changeUserPasswordHandler: $this->changeUserPasswordHandler
        );
        $this->user = $this->buildUser();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function buildUser(): User
    {
        return new User(
            id: UserId::next(),
            name: fake()->name(),
            email: new Email(fake()->email()),
            password: fake()->word(),
        );
    }

    public function test_register()
    {
        $command = new RegisterUserCommand(
            name: $this->user->name,
            email: $this->user->email,
            password: $this->user->password
        );

        $this->registerUserHandler->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn($this->user);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(UserRegistered::class));

        $result = $this->service->register($command);

        $this->assertEquals($this->user, $result);
    }

    public function test_change_password()
    {
        $command = new ChangeUserPasswordCommand(id: $this->user->id, password: 'new_pasword');

        $this->changeUserPasswordHandler->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn($this->user);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(UserPasswordChanged::class));

        $result = $this->service->changePassword($command);

        $this->assertEquals($this->user, $result);
    }
}
