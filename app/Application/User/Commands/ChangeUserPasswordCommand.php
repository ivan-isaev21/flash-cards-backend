<?php

namespace App\Application\User\Commands;

use App\Application\User\ValueObjects\UserId;

class ChangeUserPasswordCommand
{
    public function __construct(
        public readonly UserId $id,
        public readonly string $password
    ) {}
}
