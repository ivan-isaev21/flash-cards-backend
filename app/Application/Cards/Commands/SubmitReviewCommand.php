<?php

namespace App\Application\Cards\Commands;

use App\Application\Cards\ValueObjects\DeckItemId;
use App\Application\User\ValueObjects\UserId;

class SubmitReviewCommand
{
    public function __construct(
        public readonly DeckItemId $deckItemId,
        public readonly UserId $userId,
        public readonly int $quality
    ) {}
}
