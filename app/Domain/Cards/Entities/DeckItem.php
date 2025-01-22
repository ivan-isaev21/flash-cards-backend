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
        public readonly ?DateTimeImmutable $createdAt = null,
        public readonly ?DateTimeImmutable $updatedAt = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'card' => $this->card->toArray(),
            'deck' => $this->deck->toArray(),
            'createdAt' => $this->createdAt != null ? $this->createdAt->format('Y-m-d H:i:s') : null,
            'updatedAt' => $this->updatedAt != null ? $this->updatedAt->format('Y-m-d H:i:s') : null,
        ];
    }
}
