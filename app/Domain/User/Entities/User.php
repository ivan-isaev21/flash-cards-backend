<?php

namespace App\Domain\User\Entities;

use App\Application\Shared\ValueObjects\Email;
use App\Application\User\ValueObjects\Token;
use App\Application\User\ValueObjects\UserId;
use DateTimeImmutable;

class User
{
    public function __construct(
        public readonly UserId $id,
        public readonly string $name,
        public readonly Email $email,
        public readonly string $password,
        public readonly ?DateTimeImmutable $emailVerifiedAt = null,
        public readonly ?Token $verifiedToken = null,
        public readonly ?DateTimeImmutable $createdAt = null,
        public readonly ?DateTimeImmutable $updatedAt = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'name' => $this->name,
            'email' => $this->email->getValue(),
            'createdAt' => $this->createdAt != null ? $this->createdAt->format('Y-m-d H:i:s') : null,
            'updatedAt' => $this->updatedAt != null ? $this->updatedAt->format('Y-m-d H:i:s') : null,
        ];
    }
}
