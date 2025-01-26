<?php

namespace App\Application\User\Contracts;

use App\Application\User\ValueObjects\Token;
use App\Application\User\ValueObjects\UserId;

interface TokenGenerator
{
    public function generate(UserId $id): Token;
}
