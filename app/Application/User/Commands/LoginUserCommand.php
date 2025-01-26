<?php

namespace App\Application\User\Commands;

use App\Application\Shared\ValueObjects\Email;

class LoginUserCommand
{
    public function __construct(
        public readonly Email $email,
        public readonly string $password
    ) {}
}
