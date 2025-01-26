<?php

namespace App\Domain\User\Events;

use App\Application\User\ValueObjects\UserId;

class UserPasswordChanged
{
    public function __construct(
        public readonly UserId $id
    ) {}
}
