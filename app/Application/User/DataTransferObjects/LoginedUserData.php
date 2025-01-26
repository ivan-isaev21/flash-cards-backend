<?php

namespace App\Application\User\DataTransferObjects;

use App\Application\User\ValueObjects\Token;
use App\Domain\User\Entities\User;

class LoginedUserData
{
    public function __construct(
        public readonly User $user,
        public readonly Token $token,
    ) {}

    public function toArray(): array
    {
        return [
            'user' => $this->user->toArray(),
            'token' => $this->token->toArray()
        ];
    }
}
