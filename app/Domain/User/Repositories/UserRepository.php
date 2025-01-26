<?php

namespace App\Domain\User\Repositories;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\ValueObjects\UserId;
use App\Domain\User\Entities\User;

interface UserRepository
{
    public function findUserById(UserId $id): ?User;
    public function findUserByEmail(Email $email): ?User;
    public function register(User $user): User;
    public function changePassword(UserId $id, string $password): User;
}
