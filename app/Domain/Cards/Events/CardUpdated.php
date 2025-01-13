<?php

namespace App\Domain\Cards\Events;

use App\Application\Cards\ValueObjects\CardId;

class CardUpdated
{
    public function __construct(
        public readonly CardId $id
    ) {}
}
