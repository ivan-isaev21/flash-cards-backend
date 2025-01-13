<?php

namespace App\Domain\Cards\Events;

use App\Application\Cards\ValueObjects\CardId;

class CardDublicated
{
    public function __construct(
        public readonly CardId $originalId,
        public readonly CardId $dublicatedId,
    ) {}
}
