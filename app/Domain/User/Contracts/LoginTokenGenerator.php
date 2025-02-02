<?php

namespace App\Domain\User\Contracts;

use App\Application\User\ValueObjects\Token;
use App\Application\User\ValueObjects\UserId;

interface LoginTokenGenerator
{
    public function generate(UserId $id): Token;
}
