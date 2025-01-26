<?php

namespace App\Application\User\Commands;

use App\Application\Shared\ValueObjects\Email;

class RegisterUserCommand
{
    public function __construct(
        public readonly string $name,
        public readonly Email $email,
        public readonly string $password,
    ) {}
}
