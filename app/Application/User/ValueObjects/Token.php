<?php

namespace App\Application\User\ValueObjects;

use DateTimeImmutable;

class Token
{
    private string $value;
    private string $type;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $expiredAt;

    public function __construct(
        string $value,
        string $type,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $expiredAt = null
    ) {
        $this->value = $value;
        $this->type = $type;
        $this->createdAt = $createdAt;
        $this->expiredAt = $expiredAt;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isExpired(): bool
    {
        if ($this->expiredAt === null) {
            return false;
        }

        return $this->expiredAt <= new DateTimeImmutable();
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function equals(Token $other): bool
    {
        return $this->getValue() == $other->getValue() && $this->type === $other->type;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function toArray(): array
    {
        return [
            'value' => $this->getValue(),
            'type' => $this->type,
            'createdAt' => $this->createdAt != null ? $this->createdAt->format('Y-m-d H:i:s') : null,
            'expiredAt' => $this->expiredAt != null ? $this->expiredAt->format('Y-m-d H:i:s') : null,
        ];
    }
}
