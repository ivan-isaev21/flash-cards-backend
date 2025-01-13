<?php

namespace App\Domain\Cards\Events;

use App\Application\Cards\ValueObjects\DeckId;

class DeckDeleted
{
    public function __construct(
        public readonly DeckId $id
    ) {}
}
