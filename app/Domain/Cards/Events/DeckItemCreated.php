<?php

namespace App\Domain\Cards\Events;

use App\Application\Cards\ValueObjects\DeckItemId;

class DeckItemCreated
{
    public function __construct(
        public readonly DeckItemId $id
    ) {}
}
