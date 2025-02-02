<?php

namespace App\Infrastructure;

use App\Application\User\ValueObjects\Token;
use App\Domain\User\Contracts\VerifyTokenGenerator;
use DateTimeImmutable;

class LaravelVerifyTokenGenerator implements VerifyTokenGenerator
{
    public function generate(): Token
    {
        return new Token(
            value: fake()->uuid(),
            type: 'verify-token',
            createdAt: new DateTimeImmutable('now'),
            expiredAt: (new DateTimeImmutable())->modify('+1 day')
        );
    }
}
