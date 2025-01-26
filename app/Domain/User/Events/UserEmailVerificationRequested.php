<?php

namespace App\Domain\User\Events;

use App\Application\User\ValueObjects\UserId;

class UserEmailVerificationRequested
{
    public function __construct(
        public readonly UserId $id
    ) {}
}
