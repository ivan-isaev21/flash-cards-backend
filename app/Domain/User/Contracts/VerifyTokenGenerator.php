<?php

namespace App\Application\User\Contracts;

use App\Application\User\ValueObjects\Token;

interface VerifyTokenGenerator
{
    public function generate(): Token;
}
