<?php

namespace App\Application\Cards\Commands;

use App\Application\Cards\ValueObjects\CardId;
use App\Application\Cards\ValueObjects\DeckId;
use App\Application\Cards\ValueObjects\DeckItemId;

class UpdateDeckItemCommand
{
    public function __construct(
        public readonly DeckItemId $id,
        public readonly ?DeckId $deckId = null,
        public readonly ?CardId $cardId = null
    ) {}
}
