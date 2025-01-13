<?php

namespace App\Domain\Cards\Events;

use App\Application\Cards\ValueObjects\DeckId;

class DeckUpdated
{
    public function __construct(
        public readonly DeckId $id
    ) {}
}
