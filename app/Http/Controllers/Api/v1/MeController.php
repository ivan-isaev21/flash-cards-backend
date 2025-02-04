<?php

namespace App\Http\Controllers\Api\v1;

use App\Application\User\Commands\UpdateUserCommand;
use App\Application\User\Queries\GetUserQuery;
use App\Application\User\ValueObjects\UserId;
use App\Domain\User\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeUserPasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MeController extends Controller
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function show(Request $request)
    {
        $user = $this->service->show(new GetUserQuery(new UserId($request->user()->id)));
        return response(new UserResource($user), Response::HTTP_OK);
    }

    public function changePassword(ChangeUserPasswordRequest $request): Response
    {
        $this->service->changePassword($request->getChangeUserPasswordCommand(new UserId($request->user()->id)));
        return response(['message' => 'Password success changed!'], Response::HTTP_ACCEPTED);
    }

    public function update(UpdateUserRequest $request): Response
    {
        $user =  $this->service->update($request->getUpdateUserCommand(new UserId($request->user()->id)));
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function statistics() {}
}
