<?php

namespace App\Application\User\Commands;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\ValueObjects\Token;

class ResetUserPasswordCommand
{
    public function __construct(
        public readonly Email $email,
        public readonly Token $token,
        public readonly string $password
    ) {}
}
