<?php

namespace App\Application\User\Commands;

use App\Application\User\ValueObjects\UserId;

class VerifyUserEmailCommand
{
    public function __construct(
        public readonly UserId $id,
        public readonly string $token
    ) {}
}
