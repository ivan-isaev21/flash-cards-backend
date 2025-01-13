<?php

namespace App\Domain\Cards\Entities;

use App\Application\Cards\ValueObjects\CardId;
use App\Application\Shared\Enums\Locale;
use App\Application\User\ValueObjects\UserId;
use DateTimeImmutable;

class Card
{
    public function __construct(
        public readonly CardId $id,
        public readonly Locale $locale,
        public readonly string $question,
        public readonly string $answer,
        public readonly array $keywords,
        public readonly UserId $createdBy,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt,
    ) {}
}
