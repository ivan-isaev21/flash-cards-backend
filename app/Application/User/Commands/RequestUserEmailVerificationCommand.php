<?php

namespace App\Application\User\Commands;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\ValueObjects\UserId;

class RequestUserEmailVerificationCommand
{
    public function __construct(
        public readonly Email $email
    ) {}
}
