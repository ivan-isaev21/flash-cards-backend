<?php

namespace App\Domain\Cards\Events;

use App\Application\Cards\ValueObjects\DeckItemId;

class DeckItemUpdated
{
    public function __construct(
        public readonly DeckItemId $id
    ) {}
}
