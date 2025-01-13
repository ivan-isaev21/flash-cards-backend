<?php

namespace App\Domain\Cards\Events;

use App\Application\Cards\ValueObjects\DeckItemId;

class DeckItemDeleted
{
    public function __construct(
        public readonly DeckItemId $id
    ) {}
}
