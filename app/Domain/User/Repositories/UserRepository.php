<?php

namespace App\Domain\User\Repositories;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\ValueObjects\Token;
use App\Application\User\ValueObjects\UserId;
use App\Domain\User\Entities\User;
use DateTimeImmutable;

interface UserRepository
{
    public function findUserById(UserId $id): ?User;
    public function findUserByEmail(Email $email): ?User;
    public function register(User $user): User;
    public function changePassword(UserId $id, string $password): User;
    public function save(User $user): User;
    public function requestEmailVerification(UserId $id, Token $verifyToken): User;
    public function verifyEmail(UserId $id, DateTimeImmutable $emailVerifiedAt): User;
}
