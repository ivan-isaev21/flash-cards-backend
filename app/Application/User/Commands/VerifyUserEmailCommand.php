<?php

namespace App\Application\User\Commands;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\ValueObjects\UserId;

class VerifyUserEmailCommand
{
    public function __construct(
        public readonly Email $email,
        public readonly string $token
    ) {}
}
