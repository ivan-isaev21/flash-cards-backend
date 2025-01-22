<?php

namespace App\Domain\Cards\Entities;

use App\Application\Cards\ValueObjects\DeckItemId;
use App\Application\Cards\ValueObjects\SpacedRepetitionId;
use App\Application\User\ValueObjects\UserId;
use DateTimeImmutable;

class SpacedRepetition
{
    public function __construct(
        public readonly DeckItem $deckItem,
        public readonly UserId $userId,
        public readonly float $easiness = 2.5,
        public readonly int $repetition = 0,
        public readonly int $interval = 0,
        public ?DateTimeImmutable $nextDate = null,
        public readonly ?DateTimeImmutable $createdAt = null,
        public readonly ?DateTimeImmutable $updatedAt = null,
        public readonly ?SpacedRepetitionId $id = null,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id !== null ? $this->id->getValue() : null,
            'deckItem' => $this->deckItem->toArray(),
            'userId' => $this->userId->getValue(),
            'easiness' => $this->easiness,
            'repetition' => $this->repetition,
            'interval' => $this->interval,
            'nextDate ' => $this->nextDate != null ? $this->createdAt->format('Y-m-d H:i:s') : null,
            'createdAt' => $this->createdAt != null ? $this->createdAt->format('Y-m-d H:i:s') : null,
            'updatedAt' => $this->updatedAt != null ? $this->updatedAt->format('Y-m-d H:i:s') : null,
        ];
    }
}
