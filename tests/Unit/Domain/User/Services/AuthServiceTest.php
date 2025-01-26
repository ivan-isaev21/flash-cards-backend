<?php

namespace Tests\Unit\Domain\User\Services;

use App\Application\Shared\ValueObjects\Email;
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
use App\Application\User\ValueObjects\Token;
use App\Application\User\ValueObjects\UserId;
use App\Domain\User\Entities\User;
use App\Domain\User\Events\UserEmailVerificationRequested;
use App\Domain\User\Events\UserEmailVerified;
use App\Domain\User\Events\UserLogined;
use App\Domain\User\Events\UserPasswordChanged;
use App\Domain\User\Events\UserRegistered;
use App\Domain\User\Events\UserUpdated;
use App\Domain\User\Services\AuthService;
use PHPUnit\Framework\TestCase;
use Illuminate\Contracts\Events\Dispatcher;
use Mockery;

class AuthServiceTest extends TestCase
{
    private Dispatcher $dispatcher;
    private RegisterUserHandler $registerUserHandler;
    private ChangeUserPasswordHandler $changeUserPasswordHandler;
    private UpdateUserHandler $updateUserHandler;
    private LoginUserHandler $loginUserHandler;
    private VerifyUserEmailHandler $verifyUserEmailHandler;
    private RequestUserEmailVerificationHandler $requestUserEmailVerificationHandler;
    private AuthService $service;
    private User $user;
    private Token $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dispatcher = Mockery::mock(Dispatcher::class);
        $this->registerUserHandler = Mockery::mock(RegisterUserHandler::class);
        $this->changeUserPasswordHandler = Mockery::mock(ChangeUserPasswordHandler::class);
        $this->updateUserHandler = Mockery::mock(UpdateUserHandler::class);
        $this->loginUserHandler = Mockery::mock(LoginUserHandler::class);
        $this->verifyUserEmailHandler = Mockery::mock(VerifyUserEmailHandler::class);
        $this->requestUserEmailVerificationHandler = Mockery::mock(RequestUserEmailVerificationHandler::class);
        $this->token = Mockery::mock(Token::class);
        $this->service = new AuthService(
            dispatcher: $this->dispatcher,
            registerUserHandler: $this->registerUserHandler,
            changeUserPasswordHandler: $this->changeUserPasswordHandler,
            updateUserHandler: $this->updateUserHandler,
            loginUserHandler: $this->loginUserHandler,
            verifyUserEmailHandler: $this->verifyUserEmailHandler,
            requestUserEmailVerificationHandler: $this->requestUserEmailVerificationHandler
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

    public function test_update()
    {
        $command = new UpdateUserCommand(id: $this->user->id, name: $this->user->name, email: $this->user->email);

        $this->updateUserHandler->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn($this->user);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(UserUpdated::class));

        $result = $this->service->update($command);

        $this->assertEquals($this->user, $result);
    }

    public function test_login()
    {
        $command = new LoginUserCommand($this->user->email, 'password');
        $loginedUserData = new LoginedUserData($this->user, $this->token);

        $this->loginUserHandler->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn($loginedUserData);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(UserLogined::class));

        $result = $this->service->login($command);

        $this->assertEquals($loginedUserData, $result);
    }

    public function test_verify_email()
    {
        $command = new VerifyUserEmailCommand(id: $this->user->id, token: 'token');
        $this->verifyUserEmailHandler->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn($this->user);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(UserEmailVerified::class));

        $result = $this->service->verifyEmail($command);

        $this->assertEquals($this->user, $result);
    }

    public function test_request_verify_email()
    {
        $command = new RequestUserEmailVerificationCommand(id: $this->user->id);
        $this->requestUserEmailVerificationHandler->shouldReceive('handle')
            ->once()
            ->with($command)
            ->andReturn($this->user);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::type(UserEmailVerificationRequested::class));

        $result = $this->service->requestVerifyEmail($command);

        $this->assertEquals($this->user, $result);
    }
}
