<?php

namespace App\Application\User\Commands;

use App\Application\Shared\ValueObjects\Email;

class RequestResetUserPasswordCommand
{
    public function __construct(
        public readonly Email $email
    ) {}
}
