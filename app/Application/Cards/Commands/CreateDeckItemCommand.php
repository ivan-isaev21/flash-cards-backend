<?php

namespace App\Application\Cards\Commands;

use App\Application\Cards\ValueObjects\CardId;
use App\Application\Cards\ValueObjects\DeckId;

class CreateDeckItemCommand
{
    public function __construct(
        public readonly DeckId $deckId,
        public readonly CardId $cardId
    ) {}
}
