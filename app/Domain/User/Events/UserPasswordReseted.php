<?php

namespace App\Domain\User\Events;

use App\Application\User\ValueObjects\UserId;

class UserPasswordReseted
{
    public function __construct(
        public readonly UserId $id
    ) {}
}
