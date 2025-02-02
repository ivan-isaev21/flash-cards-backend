<?php

namespace App\Application\User\Commands;

use App\Application\Shared\ValueObjects\Email;

class ResetUserPasswordCommand
{
    public function __construct(
        public readonly Email $email,
        public readonly string $token,
        public readonly string $password
    ) {}
}
