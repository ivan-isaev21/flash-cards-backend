<?php

namespace App\Domain\Cards\Entities;

use App\Application\Cards\Enums\DeckType;
use App\Application\Cards\ValueObjects\DeckId;
use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;
use DateTimeImmutable;

class Deck
{
    public function __construct(
        public readonly DeckId $id,
        public readonly string $name,
        public readonly Locale $locale,
        public readonly DeckType $type,
        public readonly UserId $createdBy,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'name' => $this->name,
            'locale' => $this->locale->value,
            'type' => $this->type->value,
            'createdBy' => $this->createdBy->getValue(),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
