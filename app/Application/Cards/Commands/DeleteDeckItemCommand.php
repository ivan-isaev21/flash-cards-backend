<?php

namespace App\Application\Cards\Commands;

use App\Application\Cards\ValueObjects\DeckItemId;

class DeleteDeckItemCommand
{
    public function __construct(
        public readonly DeckItemId $id,
    ) {}
}
