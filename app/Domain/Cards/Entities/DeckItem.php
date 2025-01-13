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
}
