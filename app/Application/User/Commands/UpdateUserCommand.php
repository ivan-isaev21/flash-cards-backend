<?php

namespace App\Application\User\Commands;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\ValueObjects\UserId;

class UpdateUserCommand
{
    public function __construct(
        public readonly UserId $id,
        public readonly string $name,
        public readonly Email $email,
    ) {}
}
