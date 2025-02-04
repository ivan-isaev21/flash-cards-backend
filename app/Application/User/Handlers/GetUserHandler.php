<?php

namespace App\Application\User\Handlers;

use App\Application\User\Queries\GetUserQuery;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Repositories\UserRepository;

class GetUserHandler
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetUserQuery $query): User
    {
        $user = $this->repository->findUserById($query->id);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
