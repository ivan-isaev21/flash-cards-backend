<?php

namespace App\Http\Controllers\Api\v1;

use App\Application\User\ValueObjects\UserId;
use App\Domain\User\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeUserPasswordRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\RequestResetPasswordRequest;
use App\Http\Requests\RequestUserEmailVerificationRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\VerifyUserEmailRequest;
use App\Http\Resources\LoginedUserResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterUserRequest $request): Response
    {
        $this->service->register($request->getRegisterUserCommand());
        return response(['message' => 'You success registered!'], Response::HTTP_CREATED);
    }

    public function login(LoginUserRequest $request): Response
    {
        $userData = $this->service->login($request->getLoginUserCommand());
        return response(new LoginedUserResource($userData), Response::HTTP_OK);
    }

    public function requestResetPassword(RequestResetPasswordRequest $request): Response
    {
        $this->service->requestResetPassword($request->getRequestResetUserPasswordCommand());
        return response(['message' => 'If user with this email exists we send email!'], Response::HTTP_ACCEPTED);
    }

    public function resetPassword(ResetPasswordRequest $request): Response
    {
        $this->service->resetPassword($request->getResetUserPasswordCommand());
        return response(['message' => 'Password success changed!'], Response::HTTP_ACCEPTED);
    }

    public function requestEmailVerification(RequestUserEmailVerificationRequest $request): Response
    {
        $this->service->requestEmailVerification($request->getRequestUserVerifyEmailCommand());
        return response(['message' => 'If user with this email exists we send email!', Response::HTTP_ACCEPTED]);
    }

    public function verifyEmail(VerifyUserEmailRequest $request)
    {
        $this->service->verifyEmail($request->getVerifyUserEmailCommand());
        return response(['message' => 'Email success verified!'], Response::HTTP_ACCEPTED);
    }
}
