<?php

namespace App\Http\Controllers\Api\v1;

use App\Domain\User\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\RequestResetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    private AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterUserRequest $request): Response
    {
        $user = $this->service->register($request->getRegisterUserCommand());

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    public function requestResetPassword(RequestResetPasswordRequest $request): Response
    {
        $user = $this->service->requestResetPassword($request->getRequestResetUserPasswordCommand());
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function resetPassword(ResetPasswordRequest $request): Response
    {
        $user = $this->service->resetPassword($request->getResetUserPasswordCommand());
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }
}
