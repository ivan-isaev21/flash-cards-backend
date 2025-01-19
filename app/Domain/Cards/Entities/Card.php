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

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'locale' => $this->locale->value,
            'question' => $this->question,
            'answer' => $this->answer,
            'keywords' => $this->keywords,
            'createdBy' => $this->createdBy->getValue(),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
