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
}
