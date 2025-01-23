<?php

namespace App\Application\Cards\Queries;

use App\Application\Cards\ValueObjects\DeckId;
use App\Application\User\ValueObjects\UserId;

class GetCardToReviewQuery
{
    public function __construct(
        public readonly DeckId $deckId,
        public readonly UserId $userId,
    ) {}
}
