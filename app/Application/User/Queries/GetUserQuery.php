<?php

namespace App\Application\User\Queries;

use App\Application\User\ValueObjects\UserId;

class GetUserQuery
{
    public function __construct(
        public readonly UserId $id
    ) {}
}
