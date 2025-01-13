<?php

namespace App\Application\Cards\Queries;

use App\Application\Cards\ValueObjects\DeckId;

class GetDeckItemsQuery
{
    public function __construct(
        public readonly DeckId $deckId,
        public readonly int $page = 1,
        public readonly int $perPage = 15
    ) {}
}
