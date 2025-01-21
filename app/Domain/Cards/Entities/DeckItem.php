<?php

namespace App\Domain\Cards\Entities;

use App\Application\Cards\ValueObjects\DeckItemId;
use DateTimeImmutable;

class DeckItem
{
    public function __construct(
        public readonly DeckItemId $id,
        public readonly Deck $deck,
        public readonly Card $card,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'card' => $this->card->toArray(),
            'deck' => $this->deck->toArray(),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
