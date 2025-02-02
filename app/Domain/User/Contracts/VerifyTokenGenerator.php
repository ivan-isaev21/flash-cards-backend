<?php

namespace App\Domain\User\Contracts;

use App\Application\User\ValueObjects\Token;

interface VerifyTokenGenerator
{
    public function generate(): Token;
}
