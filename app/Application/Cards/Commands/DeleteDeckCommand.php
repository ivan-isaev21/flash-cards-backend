<?php

namespace App\Application\Cards\Commands;

use App\Application\Cards\ValueObjects\DeckId;

class DeleteDeckCommand
{
    public function __construct(
        public readonly DeckId $id,
    ) {}
}
